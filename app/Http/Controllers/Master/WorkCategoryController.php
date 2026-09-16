<?php

namespace App\Http\Controllers\Master;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\WorkCategory;
use App\Models\WorkType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkCategoryController extends Controller
{
    public function index()
    {
        $categories = WorkCategory::withCount('work_types')->orderBy('work_category_name')->get();
        return view('masters.work_category.index', compact('categories'));
    }

    public function create()
    {
        return view('masters.work_category.form')->render();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'work_category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $category = WorkCategory::create(['work_category_name' => $request->work_category_name]);
        LogActivity::addToLog('Saved Work Category', 'Work Category', $category->work_category_id);

        return back()->with('success', 'कार्य श्रेणी जोड़ी गई');
    }

    public function show(WorkCategory $work_category)
    {
        return view('masters.work_category.show', ['category' => $work_category])->render();
    }

    public function edit(WorkCategory $work_category)
    {
        return view('masters.work_category.form', ['category' => $work_category])->render();
    }

    public function update(Request $request, WorkCategory $work_category)
    {
        $validator = Validator::make($request->all(), [
            'work_category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $work_category->update(['work_category_name' => $request->work_category_name]);
        LogActivity::addToLog('Updated Work Category', 'Work Category', $work_category->work_category_id);

        return back()->with('success', 'कार्य श्रेणी अद्यतन की गई');
    }

    public function destroy(WorkCategory $work_category)
    {
        $inUse = WorkType::where('work_category_id', $work_category->work_category_id)->count();
        if ($inUse) {
            return back()->with('error', 'यह श्रेणी '.$inUse.' कार्य प्रकारों से संबंधित है, इसे हटाया नहीं जा सकता');
        }

        LogActivity::addToLog('Deleted Work Category', 'Work Category', $work_category->work_category_id);
        $work_category->delete();

        return back()->with('success', 'कार्य श्रेणी हटाई गई');
    }
}
