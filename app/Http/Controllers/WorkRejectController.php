<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\work;
use App\Models\WorkReject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkRejectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $validator = Validator::make($request->all(), [
            'work_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $reject = new WorkReject();
        $reject->rejected_date = $request->work_rejection_date;
        $reject->remark = $request->remark;
        $reject->work_id = $request->work_id;
        $reject->save();
        $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>$request->work_status,]);
        LogActivity::addToLog('Saved Work Rejected','Work Reject',$reject->id,$request->work_id);
        return redirect()->route('work.index')->with('success','Work Rejected Added Successfully !');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkReject  $workReject
     * @return \Illuminate\Http\Response
     */
    public function show(WorkReject $workReject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkReject  $workReject
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkReject $workReject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkReject  $workReject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkReject $workReject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkReject  $workReject
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkReject $workReject)
    {
        //
    }
}
