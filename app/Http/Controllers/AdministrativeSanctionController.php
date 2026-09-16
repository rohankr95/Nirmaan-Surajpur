<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\AdministrativeSanction;
use App\Models\District;
use App\Models\Work;
use App\Models\WorkStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdministrativeSanctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workBuilder = Work::query();
        $workBuilder->whereIn('work_status',[1,2,3,4,5,7]);
        if(is_officer())
        {
            $workBuilder->where('office_id',session()->get('office_id'));
        }
        if(is_emp())
        {
            $workBuilder->where('employee_id',session()->get('emp_id'));
        }
        $work_data = $workBuilder->latest()->get();
//        $work_data = Work::where('work_status',1)->orWhere('work_status',3)->orWhere('work_status',4)->orWhere('work_status',5)->get();
        $title = 'प्रशासकीय  स्वीकृति';
        $as = true;
        return view('reports.works.works',compact('request','work_data','as','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $district = District::get();
        $work = Work::get();
        return view('Administrative-Sanction.form', compact('district', 'work'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'work_id' => 'required',
            'as_no' => 'required',
            'as_submission_Date' => 'required',
            'as_amount' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $as = new AdministrativeSanction();
        $as->work_id = $request->work_id;
        $as->govt_or_district = $request->as_by;
        $as->as_no = $request->as_no;
        $as->submission_date = $request->as_submission_Date;
        $as->approval_date = $request->as_approval_date;
        $as->as_amount = $request->as_amount;
        $as->upload_file = store_upload($request->file, 'As') ?? $as->upload_file;

        $as->remark = $request->remark;
        $as->save();
        $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>$request->work_status,'as_id'=>$as->as_id]);
        LogActivity::addToLog('Saved AS','AS',$as->as_id,$request->work_id);
        return redirect()->route('administrative-sanction.index')->with('success', 'AS Added Successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdministrativeSanction  $administrativeSanction
     * @return \Illuminate\Http\Response
     */
    public function show(AdministrativeSanction $administrativeSanction)
    {
        return view('Administrative-Sanction.show', compact('administrativeSanction'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdministrativeSanction  $administrativeSanction
     * @return \Illuminate\Http\Response
     */
    public function edit($work)
    {
        $work = Work::find($work);
        $work_status = WorkStatus::find($work->work_status);
        $edit = 1;
        return view('Administrative-Sanction.asform',compact('work','work_status','edit'));
//        return view('Administrative-Sanction.form', compact('work', 'district', 'administrativeSanction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdministrativeSanction  $administrativeSanction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdministrativeSanction $administrativeSanction)
    {
        $validator = Validator::make($request->all(), [
            'work_id' => 'required',
            'as_no' => 'required',
            'as_submission_Date' => 'required',
            'as_amount' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $administrativeSanction->work_id = $request->work_id;
        $administrativeSanction->govt_or_district = $request->as_by;
        $administrativeSanction->as_no = $request->as_no;
        $administrativeSanction->submission_date = $request->as_submission_Date;
        $administrativeSanction->as_amount = $request->as_amount;
        $administrativeSanction->approval_date = $request->as_approval_date;
        $administrativeSanction->upload_file = store_upload($request->file, 'As') ?? $administrativeSanction->upload_file;
        $administrativeSanction->remark = $request->remark;
        $administrativeSanction->save();
        $value = Work::where('work_id',$request->work_id)->first('tenderChecked');
        if($value->tenderChecked == 1)
        {
            $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>7,'as_id'=>$administrativeSanction->as_id]);
        }
        else
        {
            $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>$request->work_status,'as_id'=>$administrativeSanction->as_id]);
        }
        LogActivity::addToLog('Updated AS','AS',$administrativeSanction->as_id,$request->work_id);
        return redirect()->route('administrative-sanction.index')->with('success', 'AS Updated Successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdministrativeSanction  $administrativeSanction
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdministrativeSanction $administrativeSanction)
    {
        if ($administrativeSanction->upload_file != '' && $administrativeSanction->upload_file != null) {
            // unlink($administrativeSanction->upload_file);
        }
        $administrativeSanction->delete();
        return redirect()->route('administrative-sanction.index')->with('success', 'AS Deleted Successfully !');
    }
}
