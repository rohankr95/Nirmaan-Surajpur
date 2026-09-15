<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    use HasFactory;
    protected $primaryKey ='work_type_id';
    protected $table ="work_types";

    public function work_stages()
    {
        return $this->hasMany(WorkTypeStage::class,'work_type_id','work_type_id');
    }
}
