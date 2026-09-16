<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\TechnicalSanction;
use App\Models\Work;
use App\Models\WorkStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class TechnicalSanctionController extends Controller
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
//        $work_data = Work::where('work_status',1)->orWhere('work_status',2)->orWhere('work_status',3)->get();
        $title = 'तकनीकी स्वीकृति';
        $ts = true;
        return view('reports.works.works',compact('request','work_data','ts','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $work = Work::get();
        return view('Technical-Sanction.form', compact('work'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'work_id' => 'required',
            'ts_no' => 'required',
            'submission_date' => 'required',
            'file'=>'required|mimes:jpeg,jpg,png,gif,pdf',

        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ts = new TechnicalSanction();
        $ts->work_id = $request->work_id;
        $ts->ts_no = $request->ts_no;
        $ts->submission_date = $request->submission_date;
        $ts->ts_amount = $request->ts_amount;
        $ts->approval_date = $request->approval_date;
        $ts->upload_file = store_upload($request->file, 'Ts') ?? $ts->upload_file;
        $ts->remark = $request->remark;
        $ts->save();
        $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>$request->work_status,'ts_id'=>$ts->ts_id]);
        LogActivity::addToLog('Saved TS','TS',$ts->ts_id);
        return redirect()->route('technical-sanction.index')->with('success', 'TS Added Successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TechnicalSanction  $technicalSanction
     * @return \Illuminate\Http\Response
     */
    public function show(TechnicalSanction $technicalSanction)
    {
        return view('Technical-Sanction.show',compact('technicalSanction'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TechnicalSanction  $technicalSanction
     * @return \Illuminate\Http\Response
     */
    public function edit($work)
    {
        $work = Work::find($work);
        $work_status = WorkStatus::find($work->work_status);
        $edit = 1;
        return view('Technical-Sanction.tsform',compact('work','work_status','edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TechnicalSanction  $technicalSanction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TechnicalSanction $technicalSanction)
    {
        $validator = FacadesValidator::make($request->all(), [
            'work_id' => 'required',
            'ts_no' => 'required',
            'submission_date' => 'required',
            'file'=>'required|mimes:jpeg,jpg,png,gif,pdf',

        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $technicalSanction->work_id = $request->work_id;
        $technicalSanction->ts_no = $request->ts_no;
        $technicalSanction->submission_date = $request->submission_date;
        $technicalSanction->ts_amount = $request->ts_amount;
        $technicalSanction->approval_date = $request->approval_date;
        $technicalSanction->upload_file = store_upload($request->file, 'Ts') ?? $technicalSanction->upload_file;
        $technicalSanction->remark = $request->remark;
        $technicalSanction->save();
        $value = Work::where('work_id',$request->work_id)->first('tenderChecked');
        if($value->tenderChecked == 1)
        {   
            $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>7,'ts_id'=>$technicalSanction->ts_id]);
        }
        else
        {
            $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>$request->work_status,'ts_id'=>$technicalSanction->ts_id]);
        }
        LogActivity::addToLog('Updated TS','TS',$technicalSanction->ts_id);
        return redirect()->route('technical-sanction.index')->with('success', 'TS Added Successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TechnicalSanction  $technicalSanction
     * @return \Illuminate\Http\Response
     */
    public function destroy(TechnicalSanction $technicalSanction)
    {
        if ($technicalSanction->upload_file != '' && $technicalSanction->upload_file != null) {
        //   unlink($technicalSanction->upload_file);
        }
        $technicalSanction->delete();
        return redirect()->route('technical-sanction.index')->with('success','TS Deleted Successfully !');
    }
}
