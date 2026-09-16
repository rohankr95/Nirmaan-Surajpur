<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDesignation;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(is_admin())
        {
            $Employee = Employee::get();
        }
        else
        {
            $Employee = Employee::where('office_id',session()->get('office_id'))->get();
        }
        return view('masters.employees.index',compact('Employee'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masters.employees.form')->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Employee $Employee)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'designation_id'=>'required',
            // 'office_id'=>'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if(is_admin())
        {
            $office = $request->office_id;
        }
        else
        {
            $office = session()->get('office_id');
        }
        $Employee = new Employee();
        $Employee->emp_name = $request->name;
        $Employee->emp_mobile = $request->mobile;
        $Employee->emp_email = $request->email;
        $Employee->emp_designation_id = $request->designation_id;
        $Employee->office_id = $office;
        $Employee->status = 1;
        $Employee->save();

        if ($Employee->generatedPassword) {
            return back()->with('success', 'कर्मचारी जोड़ा गया। लॉगिन आईडी: '.$Employee->emp_email
                .' — अस्थायी पासवर्ड: '.$Employee->generatedPassword
                .' (यह पासवर्ड दोबारा नहीं दिखाया जाएगा; पहली बार लॉगिन पर बदलना अनिवार्य होगा।)');
        }

        return back()->with('success','Added Employee Successfully !');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $Employee)
    {
        return view('masters.employees.show',compact('Employee'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $Employee)
    {
        return view('masters.employees.form',compact('Employee'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $Employee)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'designation_id'=>'required',
            // 'office_id'=>'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if(is_admin())
        {
            $office = $request->office_id;
        }
        else
        {
            $office = session()->get('office_id');
        }
        $Employee->emp_name = $request->name;
        $Employee->emp_mobile = $request->mobile;
        $Employee->emp_email = $request->email;
        $Employee->emp_designation_id = $request->designation_id;
        $Employee->office_id = $office;
        $Employee->status = 1;
        $Employee->update();
        return back()->with('success','Updated Employee  Successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $Employee)
    {
        $Employee->delete();
        return back()->with('success', 'Deleted Employee Successfully !');
    }
}
