<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\work;
use App\Models\WorkClosed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkClosedController extends Controller
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
        $validator = Validator::make($request->all(), [
            'work_id' => 'required',
            'close_date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $complete = new WorkClosed();
        $complete->close_date = $request->close_date;
        $complete->remark = $request->remark;
        $complete->work_id = $request->work_id;
        $complete->save();
        $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>$request->work_status]);
        LogActivity::addToLog('Saved Work Closed','Work Closed',$complete->id,$request->work_id);
        return redirect()->route('work.index')->with('success','Work Closed Successfully !');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkClosed  $workClosed
     * @return \Illuminate\Http\Response
     */
    public function show(WorkClosed $workClosed)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkClosed  $workClosed
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkClosed $workClosed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkClosed  $workClosed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkClosed $workClosed)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkClosed  $workClosed
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkClosed $workClosed)
    {
        //
    }
}
