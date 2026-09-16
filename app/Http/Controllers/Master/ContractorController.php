<?php

namespace App\Http\Controllers\Master;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\Agreement;
use App\Models\Contractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractorController extends Controller
{
    public function index()
    {
        $contractors = Contractor::withCount('agreements')->orderBy('contractor_name')->get();
        return view('masters.contractor.index', compact('contractors'));
    }

    public function create()
    {
        return view('masters.contractor.form')->render();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contractor_name' => 'required',
            'mobile' => 'nullable|digits_between:6,15',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $contractor = Contractor::create([
            'contractor_name' => $request->contractor_name,
            'contact_person' => $request->contact_person,
            'mobile' => $request->mobile,
            'registration_no' => $request->registration_no,
            'address' => $request->address,
            'status' => 1,
        ]);

        LogActivity::addToLog('Saved Contractor', 'Contractor', $contractor->contractor_id);
        return back()->with('success', 'ठेकेदार जोड़ा गया');
    }

    public function show(Contractor $contractor)
    {
        return view('masters.contractor.show', compact('contractor'))->render();
    }

    public function edit(Contractor $contractor)
    {
        return view('masters.contractor.form', compact('contractor'))->render();
    }

    public function update(Request $request, Contractor $contractor)
    {
        $validator = Validator::make($request->all(), [
            'contractor_name' => 'required',
            'mobile' => 'nullable|digits_between:6,15',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $contractor->update($request->only([
            'contractor_name', 'contact_person', 'mobile', 'registration_no', 'address',
        ]));

        LogActivity::addToLog('Updated Contractor', 'Contractor', $contractor->contractor_id);
        return back()->with('success', 'ठेकेदार अद्यतन किया गया');
    }

    public function destroy(Contractor $contractor)
    {
        // Work orders reference contractors, so removing one in use would leave
        // those orders pointing at nothing.
        $inUse = Agreement::where('contractor_id', $contractor->contractor_id)->count();
        if ($inUse) {
            return back()->with('error', 'यह ठेकेदार '.$inUse.' कार्य आदेशों से संबंधित है, इसे हटाया नहीं जा सकता');
        }

        LogActivity::addToLog('Deleted Contractor', 'Contractor', $contractor->contractor_id);
        $contractor->delete();
        return back()->with('success', 'ठेकेदार हटाया गया');
    }
}
