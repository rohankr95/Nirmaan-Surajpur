<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_date', 'work_order_no', 'work_order_date', 'work_order_amount',
        'contractor_id', 'upload_file', 'remark', 'work_id',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'work_id');
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id', 'contractor_id');
    }
}
