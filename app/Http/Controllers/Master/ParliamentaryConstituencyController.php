<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\ParliamentaryConstituency;
use Illuminate\Http\Request;

class ParliamentaryConstituencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parliamentaryConstituency = ParliamentaryConstituency::all();
        return view('masters.parliamentary_constituency.index',compact('parliamentaryConstituency'));
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
     * @param  \App\Models\ParliamentaryConstituency  $parliamentaryConstituency
     * @return \Illuminate\Http\Response
     */
    public function show(ParliamentaryConstituency $parliamentaryConstituency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ParliamentaryConstituency  $parliamentaryConstituency
     * @return \Illuminate\Http\Response
     */
    public function edit(ParliamentaryConstituency $parliamentaryConstituency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ParliamentaryConstituency  $parliamentaryConstituency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ParliamentaryConstituency $parliamentaryConstituency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ParliamentaryConstituency  $parliamentaryConstituency
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParliamentaryConstituency $parliamentaryConstituency)
    {
        //
    }
}
