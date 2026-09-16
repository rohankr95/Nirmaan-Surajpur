<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Agreement;
use App\Models\Work;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Nette\Utils\Validators;

class AgreementController extends Controller
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
        $agreement = new Agreement();
        $agreement->agreement_date = $request->agreement_date;
        $agreement->remark = $request->remark;
        $agreement->work_id = $request->work_id;
        $agreement->save();
        $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>$request->work_status]);
        LogActivity::addToLog('Saved Agreement','Agreement',$agreement->id,$request->work_id);
        return redirect()->route('work-progress.index')->with('success','Agreement Added Successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function show(Agreement $agreement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function edit(Agreement $agreement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agreement $agreement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agreement $agreement)
    {
        //
    }
}
