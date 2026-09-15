<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkProgress extends Model
{
    use HasFactory;
    protected $primaryKey = 'wp_id';
    public function work()
    {
        return $this->belongsTo(Work::class,'work_id','work_id');
    }
    public function workType()
    {
        return $this->belongsTo(workType::class,'work_type','work_type_id');
    }
    public function workTypeStage()
    {
        return $this->belongsTo(WorkTypeStage::class,'mb_stages_id','work_type_stage_id');
    }
    public function workStatus()
    {
        return $this->belongsTo(WorkStatus::class,'work_status_id','work_status_id');
    }
}
