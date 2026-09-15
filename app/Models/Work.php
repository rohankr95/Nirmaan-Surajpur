<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\FuncCall;

class Work extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['work_name','units_of_work','work_type_id','scheme_id','office_id','department_id','location_type_id','village_id','grampanchayat_id','block_id','ward_id','city_id','financial_year_id','employee_id'];
    protected $primaryKey = 'work_id';

    public function work_type()
    {
        return $this->belongsTo(WorkType::class,'work_type_id','work_type_id');
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class,'scheme_id','scheme_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','department_id');
    }
    public function office()
    {
        return $this->belongsTo(Office::class,'office_id','office_id');
    }
    public function village()
    {
        return $this->belongsTo(Village::class,'village_id','village_id');
    }
    public function ward()
    {
        return $this->belongsTo(Ward::class,'ward_id','ward_id');
    }
    public function financial_year()
    {
        return $this->belongsTo(FinancialYear::class,'financial_year_id','id');
    }
    public function location_type()
    {
        return $this->belongsTo(LocationType::class,'location_type_id','location_type_id');
    }
    public function technical_sanction()
    {
        return $this->belongsTo(TechnicalSanction::class,'ts_id','ts_id');
    }
    public function administrative_sanction()
    {
        return $this->belongsTo(AdministrativeSanction::class,'as_id','as_id');
    }
    public function tender()
    {
        return $this->belongsTo(Tender::class, 'tender_id', 'tender_id');
    }
    public function status()
    {
        return $this->belongsTo(WorkStatus::class, 'work_status','work_status_id');
    }
    public function stage()
    {
        return $this->belongsTo(WorkTypeStage::class, 'work_stage','work_type_stage_id');
    }
    public function work_progress()
    {
        return $this->hasMany(WorkProgress::class,'work_id','work_id');
    }
    public function work_complete()
    {
        return $this->belongsTo(WorkComplete::class,'work_id','work_id');
    }
    public function work_reject()
    {
        return $this->belongsTo(WorkReject::class,'work_id','work_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id','emp_id');
    }
}
