<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterController extends Controller
{
    //
    public function parliamentary_constituency(Request $request)
    {
        return view('masters.parliamentary_constituency');
    }
    public function assembly_constituency(Request $request)
    {
        return view('masters.assembly_constituency');
    }
    public function states(Request $request)
    {
        return view('masters.states');
    }
    public function schemes(Request $request)
    {
        return view('masters.scheme.schemes');
    }
}
