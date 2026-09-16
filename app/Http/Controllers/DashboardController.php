<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\AdministrativeSanction;
use App\Models\City;
use App\Models\FinancialYear;
use App\Models\Office;
use App\Models\TechnicalSanction;
use App\Models\Work;
use App\Models\WorkPayment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        //Fetching Work Status Data
        $workBuilder = Work::query();
        if(is_officer())
        {
            $workBuilder->where('office_id',session()->get('office_id'));
        }
        if(is_emp())
        {
            $workBuilder->where('employee_id',session()->get('emp_id'));
        }
        $totalCountBuilder = $workBuilder;
        $status_total_works = $totalCountBuilder->count();


        $temp_data = [];
        foreach (get_work_statuses() as $status)
        {
            $tempBuilder = clone $workBuilder;
            $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
        }
        $status_data = $temp_data;

        // Financial position across the same set of works this user can see.
        $scopedWorkIds = (clone $workBuilder)->select('work_id');

        $finance = [
            'sanctioned' => (float) (clone $workBuilder)->sum('sanction_amount'),
            'released'   => (float) WorkPayment::whereIn('work_id', $scopedWorkIds)->ofType('released')->sum('amount'),
            'spent'      => (float) WorkPayment::whereIn('work_id', $scopedWorkIds)->ofType('expenditure')->sum('amount'),
        ];
        $finance['unreleased'] = $finance['sanctioned'] - $finance['released'];

        $geo_pending = (clone $workBuilder)->whereNull('latitude')->count();

        // Compliance: completed works still missing their certificates. Only
        // completed works are counted, since the certificates are not due before.
        $completed = (clone $workBuilder)->where('work_status', 10);
        $doc_pending = [
            'uc'  => (clone $completed)->whereDoesntHave('documents', fn ($q) => $q->where('doc_type', 'uc'))->count(),
            'cc'  => (clone $completed)->whereDoesntHave('documents', fn ($q) => $q->where('doc_type', 'cc'))->count(),
            'rwh' => (clone $completed)->whereDoesntHave('documents', fn ($q) => $q->where('doc_type', 'rwh'))->count(),
        ];


        //Fetching Financial Year Data
        $financial_years_data = FinancialYear::all();
        foreach ($financial_years_data as $finyear) {
            $workBuilder = Work::query();
            if(is_officer())
        {
            $workBuilder->where('office_id',session()->get('office_id'));
        }
        if(is_emp())
        {
            $workBuilder->where('employee_id',session()->get('emp_id'));
        }
            $workBuilder->where('financial_year_id',$finyear->id);
            if(is_officer())
        {
            $workBuilder->where('office_id',session()->get('office_id'));
        }
        if(is_emp())
        {
            $workBuilder->where('employee_id',session()->get('emp_id'));
        }
            $totalCountBuilder = $workBuilder;
            $finyear->total_works  = $totalCountBuilder->count();
            $fy_data = array();

            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $fy_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $finyear->fy_work_stage = $fy_data;
        }

        //Fetching Block Data
        $block_data = Block::all();
        foreach ($block_data as $block) {
            $workBuilder = Work::query();
            $workBuilder->leftJoin('villages', 'villages.village_id', '=', 'works.village_id');
            $workBuilder->leftJoin('grampanchayats', 'grampanchayats.grampanchayat_id', '=', 'villages.grampanchayat_id');
            $workBuilder->leftJoin('blocks', 'blocks.block_id', '=', 'grampanchayats.block_id');
            $workBuilder->where('blocks.block_id', $block->block_id);
            if(is_officer())
            {
                $workBuilder->where('office_id',session()->get('office_id'));
            }
            if(is_emp())
            {
                $workBuilder->where('employee_id',session()->get('emp_id'));
            }
            $totalCountBuilder = $workBuilder;
            $block->total_works = $totalCountBuilder->count();
            $temp_data = array();
            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $block->work_stage_data = $temp_data;
        }

        //Fetching Agency Data
        $office_data = Office::where('office_id','!=',1)->get();
        if(is_officer())
        {
            $workBuilder->where('office_id',session()->get('office_id'));
        }
        if(is_emp())
        {
            $workBuilder->where('employee_id',session()->get('emp_id'));
        }
        foreach ($office_data as $office) {
            $workBuilder = Work::query();
            $workBuilder->where('office_id', $office->office_id);
            $totalCountBuilder = $workBuilder;
            $office->total_works = $totalCountBuilder->count();
            $temp_data = array();
            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $office->work_stage_data = $temp_data;
        }

         //Fetching City Data
         $city_data = City::all();
         foreach ($city_data as $city) {
             $workBuilder = Work::query();
             $workBuilder->leftJoin('wards', 'wards.ward_id', '=', 'works.ward_id');
             $workBuilder->leftJoin('cities', 'cities.city_id', '=', 'wards.city_id');
             $workBuilder->where('cities.city_id', $city->city_id);
             if(is_officer())
             {
                 $workBuilder->where('office_id',session()->get('office_id'));
             }
             if(is_emp())
             {
                 $workBuilder->where('employee_id',session()->get('emp_id'));
             }
             $totalCountBuilder = $workBuilder;
             $city->total_works = $totalCountBuilder->count();
             $temp_data = array();
 
             foreach (get_work_statuses() as $status)
             {
                 $tempBuilder = clone $workBuilder;
                 $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
             }
             $city->work_stage_data = $temp_data;
         }
        return view('dashboard.dashboard', compact('financial_years_data','block_data','office_data','status_total_works','status_data','city_data','finance','geo_pending','doc_pending'));
    }
    public function work_progress_ts(Request $request)
    {
        $ts = new TechnicalSanction();
        $ts->work_id = $request->work_idd;
        $ts->ts_no = $request->ts_no;
        $ts->submission_date = $request->submission_date;
        $ts->ts_amount = $request->ts_amount;
        $ts->approval_date = $request->approval_date;
        $ts->upload_file = store_upload($request->Upload_file, 'Ts') ?? $ts->upload_file;
        $ts->remark = $request->remark;
        $ts->save();
        $work  = Work::where('work_id',$request->work_idd)->update(['work_status'=>3,'ts_id'=>$ts->ts_id]);
        return redirect()->back()->with('success','Ts Updated Successfully !');
    }

}
