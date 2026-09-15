<?php

namespace App\Http\Controllers;

use App\Models\FinancialYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Helpers\LogActivity;

class FinancialYearController extends Controller
{

    public function index()
    {
       
        // $financial_years = FinancialYear::all();
        // return view('financial_year.index', compact('financial_years'));
    }
    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'from_date' => 'required',
        //     'to_date' => 'required',
        // ]);

        // $fyear = new FinancialYear();
        // $fyear->name = $request->name;
        // $fyear->from_date = $request->from_date;
        // $fyear->to_date = $request->to_date;
        // $fyear->created_by = Session::get('user_id');
        // $fyear->save();
        // LogActivity::addToLog('Saved Financial Year','Financial Year',$fyear->id);
        // return redirect()->route('financial_year.index')->with('msg', 'वित्तीय वर्ष जोड़ा गया');
    }
    public function edit($args)
    {
        // $data = FinancialYear::find(Crypt::decryptString($args));
        // $financial_years = FinancialYear::all();
        // return view('financial_year.index', compact('financial_years', 'data'));
    }
    public function update(Request $request, $args)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'from_date' => 'required',
        //     'to_date' => 'required',
        // ]);

        // $fyear = FinancialYear::find(Crypt::decryptString($args));
        // $fyear->name = $request->name;
        // $fyear->from_date = $request->from_date;
        // $fyear->to_date = $request->to_date;
        // $fyear->updated_by = Session::get('user_id');
        // $fyear->save();
        // LogActivity::addToLog('Updated Financial Year','Financial Year',$fyear->id);
        // return redirect()->route('financial_year.index')->with('msg', 'वित्तीय वर्ष अपडेट किया गया');
    }
    public function destroy($id)
    {
        // $id = Crypt::decrypt($id);
        // FinancialYear::destroy($id);
        // LogActivity::addToLog('Deleted Financial Year','Financial Year',$id);
        // return redirect()->back()->with('msg', 'रिकॉर्ड हटाएं गया !');
    }

    public function setFY_date($id)
    {

        // $FY_list = FinancialYear::find($id);
        // session()->put('FY_id', $FY_list->id);
        // session()->put('FY_name', $FY_list->name);
        // return redirect()->back()->with('mgs','वित्तीय वर्ष सेट है !');
    }
}
