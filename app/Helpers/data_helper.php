<?php

use App\Models\City;
use App\Models\EmployeeDesignation;
use App\Models\Ward;

function get_blocks()
{
    return \App\Models\Block::all();
}
function get_grampanchayats()
{
    return \App\Models\Grampanchayat::orderBy('grampanchayat_name')->get();
}
function get_work_categories()
{
    return \App\Models\WorkCategory::orderBy('work_category_name')->get();
}
function get_contractors()
{
    return \App\Models\Contractor::where('status', 1)->orderBy('contractor_name')->get();
}
function get_departments()
{
    return \App\Models\Department::all();
}
function get_offices()
{
    return \App\Models\Office::all();
}
function get_financial_years()
{
    return \App\Models\FinancialYear::all();
}
function get_schemes()
{
    return \App\Models\Scheme::all();
}

function get_work_statuses()
{
    return \App\Models\WorkStatus::all();
}
function get_new_work_code()
{
    $work = \App\Models\Work::OrderBy('work_id','desc')->first();
    if($work)
    {
        return sprintf('%06d',$work->work_id+1);
    }
    else
    {
        return "000001";
    }
}
function is_admin(){
    return (\Illuminate\Support\Facades\Session::get('user_role_id')==1);
}
function is_officer()
{
    return (\Illuminate\Support\Facades\Session::get('user_role_id')==2);
}
function is_emp()
{
    return (\Illuminate\Support\Facades\Session::get('user_role_id')==3);
}
function getWorkProgressData($workID,$workTypeStageID){
    return \App\Models\WorkProgress::where('work_id',$workID)->where('mb_stages_id',$workTypeStageID)->first();
}
function status_count()
{
    // admin
    if(session()->get('user_role_id') == 1)
    {

    }
    else
    {

    }
}
function get_designation()
{
    return EmployeeDesignation::all();
}
function get_city()
{
    return City::all();
}
function get_ward()
{
    return Ward::all();
}
