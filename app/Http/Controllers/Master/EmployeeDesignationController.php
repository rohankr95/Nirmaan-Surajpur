<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDesignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeDesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designation = EmployeeDesignation::all();
        return view('masters.employees_designation.index',compact('designation'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masters.employees_designation.form')->render();
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
            'designation_id'=>'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        for ($i=0;$i<count($request->designation_id);$i++)
        {
            $designation = new EmployeeDesignation();
            $designation->designation_name = $request->designation_id[$i];
            $designation->save();
        } 
        return back()->with('success',"Designation Added Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeDesignation $EmployeeDesignation)
    {
        return view('masters.employees_designation.show',compact('EmployeeDesignation'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeDesignation $EmployeeDesignation)
    {
        return view('masters.employees_designation.form',compact('EmployeeDesignation'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeDesignation $EmployeeDesignation)
    {
        $validator = Validator::make($request->all(), [
            'designation_name'=>'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $EmployeeDesignation->designation_name = $request->designation_name; 
        $EmployeeDesignation->save();
        return back()->with('success','Designation Updated Successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeDesignation $EmployeeDesignation)
    {
        $EmployeeDesignation->delete();
        return back()->with('suceess','Designation Deleted Suceessfully !');
    }
}
