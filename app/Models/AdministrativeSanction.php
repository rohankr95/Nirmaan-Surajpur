<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrativeSanction extends Model
{
    use HasFactory;
    protected $primaryKey = 'as_id';
    public function work()
    {
        return $this->belongsTo(Work::class,'work_id','work_id');
    }
    public function district()
    {
        return $this->belongsTo(District::class,'govt_or_district','district_id');
    }
}
