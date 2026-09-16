<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPayment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'work_id', 'payment_type', 'amount', 'payment_date',
        'financial_year_id', 'instalment_no', 'mb_no', 'mb_date',
        'remark', 'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'mb_date' => 'date',
    ];

    public const TYPES = [
        'released'    => 'जारी राशि (जिला द्वारा)',
        'expenditure' => 'व्यय राशि (विभाग द्वारा)',
        'evaluation'  => 'मूल्यांकन राशि (इंजीनियर द्वारा)',
    ];

    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->payment_type] ?? $this->payment_type;
    }

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'work_id');
    }

    public function financial_year()
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('payment_type', $type);
    }
}
