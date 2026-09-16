<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Tender;
use App\Models\Work;
use App\Models\WorkStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workBuilder = Work::query();
        $workBuilder->whereIn('work_status',[2,3,4,5,6]);
        if(is_officer())
            {
                $workBuilder->where('office_id',session()->get('office_id'));
            }
            if(is_emp())
            {
                $workBuilder->where('employee_id',session()->get('emp_id'));
            }
        $work_data = $workBuilder->latest()->get();
//        $work_data = Work::where('work_status', 5)->orWhere('work_status', 6)->get();
        $title = 'निविदा';
        $tender = true;
        return view('reports.works.works', compact('request', 'work_data', 'tender', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $work = Work::get();
        return view('Tender.form', compact('work'));
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
            'tender_no' => 'required',
            'tender_release_date' => 'required',
            'tender_opening_date' => 'required',
            // 'work_order_date'=>'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tender = new Tender();
        $tender->work_id = $request->work_id;
        $tender->tender_no = $request->tender_no;
        $tender->tender_release_date = $request->tender_release_date;
        $tender->tender_opening_date = $request->tender_opening_date;
        $tender->work_order_date = $request->work_order_date;
        $tender->upload_file = store_upload($request->file, 'Tender') ?? $tender->upload_file;

        $tender->remark = $request->remark;
        $tender->save();
        $work  = Work::where('work_id', $request->work_id)->update(['work_status' => $request->work_status,    'tender_id' => $tender->tender_id]);
        LogActivity::addToLog('Saved Tender', 'Tender', $tender->tender_id, $request->work_id);
        return redirect()->route('tender.index')->with('success', 'Tender Added Successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tender  $tender
     * @return \Illuminate\Http\Response
     */
    public function show(Tender $tender)
    {
        return view('Tender.show', compact('tender'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tender  $tender
     * @return \Illuminate\Http\Response
     */
    public function edit($work)
    {
        $work = Work::find($work);
        $work_status = WorkStatus::find($work->work_status);
        $edit = 1;
        return view('Tender.edit_modal', compact('work', 'work_status', 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tender  $tender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tender $tender)
    {
        $validator = Validator::make($request->all(), [
            'work_id' => 'required',
            'tender_no' => 'required',
            'tender_release_date' => 'required',
            'tender_opening_date' => 'required',
            'work_order_date' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tender->work_id = $request->work_id;
        $tender->tender_no = $request->tender_no;
        $tender->tender_release_date = $request->tender_release_date;
        $tender->tender_opening_date = $request->tender_opening_date;
        $tender->work_order_date = $request->work_order_date;
        $tender->upload_file = store_upload($request->file, 'Tender') ?? $tender->upload_file;
        $tender->remark = $request->remark;
        $tender->save();
        $work  = Work::where('work_id', $request->work_id)->update(['work_status' => $request->work_status,    'tender_id' => $tender->tender_id]);
        LogActivity::addToLog('Updated Tender', 'Tender', $tender->tender_id, $request->work_id);
        return redirect()->route('tender.index')->with('success', 'Tender Updated Successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tender  $tender
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tender $tender)
    {
        if ($tender->upload_file != '' && $tender->upload_file != null) {
            //   unlink($tender->upload_file);
        }
        $tender->delete();
        return redirect()->route('tender.index')->with('success', 'Tender Deleted Successfully !');
    }
}
