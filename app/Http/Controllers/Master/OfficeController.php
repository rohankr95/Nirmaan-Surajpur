<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Office;
use App\Models\Scheme;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offices = Office::all();
        return view('masters.offices.index',compact('offices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masters.offices.form')->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'name' => 'required',
//            'department' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            return back()->withErrors($validator)->withInput();
//        }
//        $scheme = new Scheme();
//        $scheme->scheme_name = $request->name;
//        $scheme->department_id = $request->department;
//        $scheme->save();
//        return back()->with('success',"Scheme Added Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function show(Office $office)
    {
        return view('masters.offices.show',compact('office'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function edit(Office $office)
    {
        return view('masters.offices.form',compact('office'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Office $office)
    {
//        $validator = Validator::make($request->all(), [
//            'name' => 'required',
//            'department' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            return back()->withErrors($validator)->withInput();
//        }
//        $scheme->scheme_name = $request->name;
//        $scheme->department_id = $request->department;
//        $scheme->save();
//        return back()->with('success',"Scheme Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function destroy(Office $office)
    {
//        $office->delete();
//        return back()->with('success',"Office Deleted Successfully");
    }
}
