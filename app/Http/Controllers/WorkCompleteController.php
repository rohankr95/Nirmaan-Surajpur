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
            'work_completion_date' => 'required|date',
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
        $details = 'पूर्णता दिनांक: ' . date('d-m-Y', strtotime($request->work_completion_date));
        LogActivity::addToLog('Saved Work Completed','Work Completed',$complete->id,$request->work_id,$details);
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
     * The form for replacing the completion photo -- also used to add one
     * back if it was deleted, since both are just "set upload_file".
     */
    public function editPhoto(WorkComplete $workComplete)
    {
        return view('work-complete.update_photo_form', compact('workComplete'))->render();
    }

    /**
     * Replaces the completion photo. completion_date/remark/work_status are
     * left untouched -- this only ever touches the photo.
     */
    public function updatePhoto(Request $request, WorkComplete $workComplete)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpeg,jpg,png,gif,webp,pdf',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $workComplete->upload_file = store_upload($request->file, 'Work-Complete') ?? $workComplete->upload_file;
        $workComplete->save();

        LogActivity::addToLog('Updated Work Completed', 'Work Completed', $workComplete->id, $workComplete->work_id);

        return back()->with('success', 'छायाचित्र अद्यतन किया गया');
    }

    /**
     * Clears the completion photo only. The completion record itself (and
     * the work's completed status) is left alone.
     */
    public function destroyPhoto(WorkComplete $workComplete)
    {
        $workComplete->upload_file = null;
        $workComplete->save();

        LogActivity::addToLog('Deleted Photo', 'Work Completed', $workComplete->id, $workComplete->work_id);

        return back()->with('success', 'छायाचित्र हटाया गया');
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
