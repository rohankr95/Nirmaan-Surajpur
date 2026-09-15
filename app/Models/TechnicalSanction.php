<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TechnicalSanction extends Model
{
    use HasFactory;
    protected $primaryKey = 'ts_id';
    public function work()
    {
        return $this->belongsTo(Work::class,'work_id','work_id');
    }
}
