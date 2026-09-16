<?php

namespace App\Http\Controllers\Master;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\Village;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VillageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $villages = Village::with('grampanchayat')->orderBy('village_name')->get();
        return view('masters.village.index', compact('villages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masters.village.form')->render();
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
            'village_name' => 'required',
            'grampanchayat_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $village = new Village();
        $village->village_name = $request->village_name;
        $village->village_name_en = $request->village_name_en;
        $village->grampanchayat_id = $request->grampanchayat_id;
        $village->save();

        LogActivity::addToLog('Saved Village', 'Village', $village->village_id);
        return back()->with('success', 'ग्राम जोड़ा गया');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Village  $village
     * @return \Illuminate\Http\Response
     */
    public function show(Village $village)
    {
        return view('masters.village.show', compact('village'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Village  $village
     * @return \Illuminate\Http\Response
     */
    public function edit(Village $village)
    {
        return view('masters.village.form', compact('village'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Village  $village
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Village $village)
    {
        $validator = Validator::make($request->all(), [
            'village_name' => 'required',
            'grampanchayat_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $village->village_name = $request->village_name;
        $village->village_name_en = $request->village_name_en;
        $village->grampanchayat_id = $request->grampanchayat_id;
        $village->save();

        LogActivity::addToLog('Updated Village', 'Village', $village->village_id);
        return back()->with('success', 'ग्राम अद्यतन किया गया');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Village  $village
     * @return \Illuminate\Http\Response
     */
    public function destroy(Village $village)
    {
        // Works reference villages by id, so removing one in use would leave
        // those works pointing at nothing.
        $inUse = Work::where('village_id', $village->village_id)->count();
        if ($inUse) {
            return back()->with('error', 'यह ग्राम '.$inUse.' कार्यों से संबंधित है, इसे हटाया नहीं जा सकता');
        }

        LogActivity::addToLog('Deleted Village', 'Village', $village->village_id);
        $village->delete();
        return back()->with('success', 'ग्राम हटाया गया');
    }
}
