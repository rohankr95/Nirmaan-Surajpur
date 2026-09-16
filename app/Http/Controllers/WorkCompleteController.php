<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Work;
use App\Models\WorkComplete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class WorkCompleteController extends Controller
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
            'file'=>'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $complete = new WorkComplete();
        $complete->completion_date = $request->work_completion_date;
        $complete->upload_file = store_upload($request->file, 'Work-Complete') ?? $complete->upload_file;
        $complete->remark = $request->remark;
        $complete->work_id = $request->work_id;
        $complete->save();
        $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>10]);
        LogActivity::addToLog('Saved Work Completed','Work Completed',$complete->id,$request->work_id);
        return redirect()->route('work.index')->with('success','Work Completed Added Successfully !');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkComplete  $workComplete
     * @return \Illuminate\Http\Response
     */
    public function show(WorkComplete $workComplete)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkComplete  $workComplete
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkComplete $workComplete)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkComplete  $workComplete
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkComplete $workComplete)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkComplete  $workComplete
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkComplete $workComplete)
    {
        //
    }
}
