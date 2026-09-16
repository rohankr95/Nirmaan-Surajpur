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
            'agreement_date' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // A work order is edited far more often than a fresh one is entered
        // (someone skipped it while updating the tender and comes back to
        // fill it in later), so re-submitting updates the existing record
        // for this work rather than piling up duplicate rows.
        $work = Work::find($request->work_id);
        $agreement = $work->agreement ?? new Agreement();
        $agreement->agreement_date = $request->agreement_date ?: null;
        $agreement->work_order_no = $request->work_order_no;
        $agreement->work_order_date = $request->work_order_date;
        $agreement->work_order_amount = $request->work_order_amount;
        $agreement->contractor_id = $request->contractor_id ?: null;
        $agreement->upload_file = store_upload($request->file, 'Agreement') ?? $agreement->upload_file;
        $agreement->remark = $request->remark;
        $agreement->work_id = $request->work_id;
        $agreement->save();
        $work->update(['work_status' => $request->work_status]);
        LogActivity::addToLog($agreement->wasRecentlyCreated ? 'Saved Agreement' : 'Updated Agreement', 'Agreement', $agreement->id, $request->work_id);
        return redirect()->route('work-progress.index')->with('success', 'Agreement Saved Successfully !');
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
