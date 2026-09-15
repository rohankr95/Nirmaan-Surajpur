<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\AssemblyConstituency;
use Illuminate\Http\Request;

class AssemblyConstituencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assemblyConstituency = AssemblyConstituency::all();
        return view('masters.assembly_constituency.index',compact('assemblyConstituency'));
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
     * @param  \App\Models\AssemblyConstituency  $assemblyConstituency
     * @return \Illuminate\Http\Response
     */
    public function show(AssemblyConstituency $assemblyConstituency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AssemblyConstituency  $assemblyConstituency
     * @return \Illuminate\Http\Response
     */
    public function edit(AssemblyConstituency $assemblyConstituency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssemblyConstituency  $assemblyConstituency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssemblyConstituency $assemblyConstituency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssemblyConstituency  $assemblyConstituency
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssemblyConstituency $assemblyConstituency)
    {
        //
    }
}
