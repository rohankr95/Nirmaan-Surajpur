<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    use HasFactory;
    protected $primaryKey = 'tender_id';
    public function work()
    {
        return $this->belongsTo(Work::class,'work_id','work_id');
    }
}
