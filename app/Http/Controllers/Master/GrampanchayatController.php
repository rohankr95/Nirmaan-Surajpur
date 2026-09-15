<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Grampanchayat;
use Illuminate\Http\Request;

class GrampanchayatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $villages = Grampanchayat::all();
        return view('masters.villages.index',compact('villages'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Grampanchayat  $grampanchayat
     * @return \Illuminate\Http\Response
     */
    public function show(Grampanchayat $grampanchayat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Grampanchayat  $grampanchayat
     * @return \Illuminate\Http\Response
     */
    public function edit(Grampanchayat $grampanchayat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grampanchayat  $grampanchayat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grampanchayat $grampanchayat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grampanchayat  $grampanchayat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grampanchayat $grampanchayat)
    {
        //
    }
}
