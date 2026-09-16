<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkDocument extends Model
{
    use HasFactory;

    protected $primaryKey = 'document_id';

    protected $fillable = [
        'work_id', 'doc_type', 'file_path', 'reference_no',
        'document_date', 'remark', 'uploaded_by',
    ];

    protected $casts = ['document_date' => 'date'];

    public const TYPES = [
        'uc'    => 'उपयोगिता प्रमाण पत्र (UC)',
        'cc'    => 'पूर्णता प्रमाण पत्र (CC)',
        'rwh'   => 'वर्षा जल संचयन (RWH)',
        'other' => 'अन्य दस्तावेज़',
    ];

    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->doc_type] ?? $this->doc_type;
    }

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'work_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'user_id');
    }
}
