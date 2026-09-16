<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\AssemblyConstituency;
use App\Models\Block;
use App\Models\City;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FinancialYear;
use App\Models\Grampanchayat;
use App\Models\LocationType;
use App\Models\Office;
use App\Models\Scheme;
use App\Models\Village;
use App\Models\Ward;
use App\Models\Work;
use App\Models\WorkType;
use App\Models\WorkTypeStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workBuilder = Work::query();
        if(is_officer())
        {
            $workBuilder->where('office_id',session()->get('office_id'));
            // $workBuilder->whereNull('employee_id');
        }
        if(is_emp())
        {
            $workBuilder->where('employee_id',session()->get('emp_id'));
        }
        $work_data = $workBuilder->latest()->get();
        $addWork = true;
        return view('reports.works.works',compact('request','work_data','addWork'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fy = FinancialYear::get();
        $dp = Department::get();
        $scheme = Scheme::get();
        $locationType = LocationType::get();
        $city= City::get();
        $block = Block::get();
        $workType = WorkType::get();
        $vidhansabha = AssemblyConstituency::get();
        $work = Work::OrderBy('work_id','desc')->first();
        $office = Office::where('office_id','!=',1)->get();
        $Employee = Employee::where('office_id',session()->get('office_id'))->get();
        $Adm_Employee = Employee::get();
        return view('work.form',compact('fy','dp','scheme','locationType','city','block','workType','vidhansabha','office','Employee','Adm_Employee'));

    }

    // public function fetchDepartmentToOffice(Request $request)
    // {
    //     $data['office'] = Office::where('department_id', $request->dp_id)->get(["office_id", "office_name"]);
    //     return response()->json($data);
    // }
    public function fetchCityToWard(Request $request)
    {
        $data['ward'] = Ward::where('city_id', $request->city_id)->get(["ward_id", "ward_name"]);
        return response()->json($data);
    }
    public function fetchBlockToGrampanchayat(Request $request)
    {
       $data['gp'] = Grampanchayat::where('block_id',$request->block_id)->get(['grampanchayat_id','grampanchayat_name']);
       return response()->json($data);
    }
    public function fetchGpToVillage(Request $request)
    {
        $data['village'] = Village::where('grampanchayat_id',$request->gp_id)->get(['village_id','village_name']);
        return response()->json($data);
    }
    public function fetchWorkTypeToMBStages(Request $request)
    {
        $data['MBstages'] = WorkTypeStage::where('work_type_id',$request->typeId)->get(['work_type_stage_id','work_type_stage_name']);
        return response()->json($data);
    }
    public function fetchOfficeToEmployee(Request $request)
    {
        $data['employee'] = Employee::where('office_id',$request->office_id)->get(['emp_id','emp_name','office_id']);
        return response()->json($data);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),[
           'work_name'=>'required',
           'fy'=>'required',
           'scheme'=>'required',
           'work_type'=>'required',
           'location_type'=>'required',
        //    'office'=>'required',
        ]);

        if($request->filled('village') && $request->filled('ward')){
            return redirect()->back()->with('success','City/Village Not Selected!');
        }
        if($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }   
        if(is_admin())
        {
            $eng_id = $request->employeeAdmin;
            $office = $request->office;
        }
        else
        {
            $eng_id = $request->employee;
            $office = session()->get('office_id');
        }
        $work = new Work();
        $work->work_name = $request->work_name;
        $work->units_of_work = $request->unit_work;
        $work->work_type_id = $request->work_type;
        $work->scheme_id = $request->scheme;
        $work->office_id = $office;
        $work->department_id = $request->dp;
        $work->location_type_id = $request->location_type;
        $work->village_id = $request->village;
        $work->ward_id = $request->ward;
        $work->financial_year_id = $request->fy;
        $work->employee_id = $eng_id??0;
        $work->work_status = 1; // 1 fo aprambh
        $work->created_by = session()->get('user_id');
        $work->dpr_startDate = $request->dpr_startDate;
        $work->dpr_endDate = $request->dpr_endDate;
        $work->ts_startDate = $request->ts_startDate;
        $work->ts_endDate = $request->ts_endDate;
        $work->as_startDate = $request->as_startDate;
        $work->as_endDate = $request->as_endDate;
        $work->tenderChecked = $request->tenderChecked ? $request->tenderChecked:0;
        $work->tender_startDate = $request->tender_startDate;
        $work->tender_endDate = $request->tender_endDate;
        $work->workOrder_startDate = $request->workOrder_startDate;
        $work->workOrder_endDate = $request->workOrder_endDate;
        $work->agreement_startDate = $request->agreement_startDate;
        $work->agreement_endDate = $request->agreement_endDate;
        $work->workStart_startDate = $request->workStart_startDate;
        $work->workComplete_endDate = $request->workComplete_endDate;
        $work->save();
        LogActivity::addToLog('Saved Work','Work',$work->work_id);
        return redirect()->route('work.index')->with('success','Work Added Successfully !');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        return view('work.show',compact('work'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        $fy = FinancialYear::get();
        $dp = Department::get();
        $scheme = Scheme::get();
        $locationType = LocationType::get();
        $city= City::get();
        $block = Block::get();
        $workType = WorkType::get();
        $office = Office::get();
        $Employee = Employee::where('office_id',session()->get('office_id'))->get();
        $Adm_Employee = Employee::get();
        return view('work.form',compact('fy','dp','scheme','locationType','city','block','workType','work','office','Employee','Adm_Employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work $work)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'work_name'=>'required',
            'fy'=>'required',
            'scheme'=>'required',
            'work_type'=>'required',
            'location_type'=>'required',
        ]);
        if($request->filled('village') && $request->filled('ward')){
            return redirect()->back()->with('success','City/Village Not Selected!');
        }
        if(is_admin())
        {
            $eng_id = $request->employeeAdmin;
            $office = $request->office;
        }
        else
        {
            $eng_id = $request->employee;
            $office = session()->get('office_id');
        }
        $work->work_name = $request->work_name;
        $work->units_of_work = $request->unit_work;
        $work->work_type_id = $request->work_type;
        $work->scheme_id = $request->scheme;
        $work->office_id = $office;
        $work->department_id = $request->dp;
        $work->location_type_id = $request->location_type;
        $work->village_id = $request->village;
        $work->ward_id = $request->ward;
        $work->financial_year_id = $request->fy;
        $work->employee_id = $eng_id;
        $work->updated_by = session()->get('user_id');
        $work->dpr_startDate = $request->dpr_startDate;
        $work->dpr_endDate = $request->dpr_endDate;
        $work->ts_startDate = $request->ts_startDate;
        $work->ts_endDate = $request->ts_endDate;
        $work->as_startDate = $request->as_startDate;
        $work->as_endDate = $request->as_endDate;
        $work->tenderChecked = $request->tenderChecked;
        $work->tender_startDate = $request->tender_startDate;
        $work->tender_endDate = $request->tender_endDate;
        $work->workOrder_startDate = $request->workOrder_startDate;
        $work->workOrder_endDate = $request->workOrder_endDate;
        $work->agreement_startDate = $request->agreement_startDate;
        $work->agreement_endDate = $request->agreement_endDate;
        $work->workStart_startDate = $request->workStart_startDate;
        $work->workComplete_endDate = $request->workComplete_endDate;
        $work->save();
        LogActivity::addToLog('Updated Work','Work',$work->id);
        return redirect()->route('work.index')->with('success','Work Updated Successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work $work)
    {
        $work->deleted_by = session()->get('user_id');
        $work->save();
        $work->delete();
        LogActivity::addToLog('Deleted Work','Work',$work->work_id);
        return redirect()->route('work.index')->with('success','Work Deleted Successfully !');
    }
}
