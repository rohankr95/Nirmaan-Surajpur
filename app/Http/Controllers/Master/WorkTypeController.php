<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use App\Models\WorkType;
use App\Models\WorkTypeStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $work_types = WorkType::latest()->get();
        return view('masters.work_types.index',compact('work_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masters.work_types.form')->render();
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
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        $workType = new WorkType();
        $workType->work_type_name = $request->name;
        $workType->work_category_id = $request->work_category_id ?: null;
        $workType->save();
        $workTypeID = $workType->work_type_id;

        for ($i=0;$i<count($request->work_stages);$i++)
        {
            $workStage = new WorkTypeStage();
            $workStage->work_type_stage_name = $request->work_stages[$i];
            $workStage->stage_number = $i+1;
            $workStage->work_type_id = $workTypeID;
            $workStage->save();
        }

//        foreach($request->work_stages as $stage){
//            if($stage)
//            {
//                $workStage = new WorkTypeStage();
//                $workStage->work_type_stage_name = $stage;
//                $workStage->work_type_id = $workTypeID;
//                $workStage->save();
//            }
//        }
        return back()->with('success',"Work Type Added Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function show(WorkType $workType)
    {
        return view('masters.work_types.show',compact('workType'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkType $workType)
    {
        return view('masters.work_types.form',compact('workType'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkType $workType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $workType->work_type_name = $request->name;
        $workType->work_category_id = $request->work_category_id ?: null;
        foreach ($workType->work_stages as $ws)
        {
            if(!in_array($ws->work_type_stage_id, $request->work_stage_id))
            {
                $ws->delete();
            }
        }
        for ($i=0;$i<count($request->work_stages);$i++)
        {
            if(isset($request->work_stage_id[$i]))
            {
                $workStage = WorkTypeStage::find($request->work_stage_id[$i]);
            }else{
                $workStage = new WorkTypeStage();
            }
            $workStage->work_type_stage_name = $request->work_stages[$i];
            $workStage->stage_number = $i+1;
            $workStage->work_type_id = $workType->work_type_id;
            $workStage->save();
        }
        $workType->save();
        return back()->with('success',"Work Type Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkType $workType)
    {
        $workType->delete();
        $workType->work_stages()->delete();
        return back()->with('success',"Work Type Deleted Successfully");
    }
}
