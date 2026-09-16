<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\AdministrativeSanction;
use App\Models\District;
use App\Models\TechnicalSanction;
use App\Models\Tender;
use App\Models\Work;
use App\Models\WorkProgress;
use App\Models\WorkStatus;
use App\Models\WorkType;
use App\Models\WorkTypeStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workBuilder = Work::query();
        $workBuilder->whereIn('work_status',[7,8,9]);
        if(is_officer())
        {
            $workBuilder->where('office_id',session()->get('office_id'));
        }
        if(is_emp())
        {
            $workBuilder->where('employee_id',session()->get('emp_id'));
        }
        $work_data = $workBuilder->latest()->get();
//        $work_data = Work::where('work_status',8)->orWhere('work_status',7)->get();
        $title = 'कार्य प्रगति स्तर';
        $wp = true;
        return view('reports.works.works',compact('request','work_data','wp','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $work_status_list = ($request->work_id)?WorkStatus::get():array();
        $work_status = WorkStatus::find($request->work_status);
        $work_type = WorkType::get();
        $stages_list = ($request->mb_stage)?WorkTypeStage::get():array();
        $stages = WorkTypeStage::get();
        $work_list = Work::get();
        $work = Work::find($request->work_id);
        $district = District::get();
        $wp = WorkProgress::get();
        return view('Work-Progress.form23', compact('work_type', 'stages', 'work','work_list', 'work_status_list','work_status','district','wp','stages_list'));
    }

    public function fetchWorkToMBStages(Request $request)
    {
        $work = Work::find($request->work_id);
         $data['stages'] = WorkTypeStage::where('work_type_id',$work->work_type_id)->get();
         return response()->json($data);
     }

     public function fetchWorkToWorkStatus(Request $request)
     {
         $work = work::find($request->work_id);
          $data['status'] = $work->work_status;
          return response()->json($data);
      }
      public function fetchStatusToTS(Request $request)
      {
        $data['ts'] = TechnicalSanction::where('work_id',$request->work_id)->first();
           return response()->json($data);
       }
       public function fetchStatusToAS(Request $request)
       {
         $data['as'] = AdministrativeSanction::where('work_id',$request->work_id)->first();
            return response()->json($data);
        }
        public function fetchStatusToTender(Request $request)
        {
          $data['Tender'] = Tender::where('work_id',$request->work_id)->first();
             return response()->json($data);
        }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'work_id' => 'required',
            // 'estimated_completion_date' => 'required',
            'mb_stages' => 'required',
            'work_status' => 'required',
            'file' => 'nullable|mimes:jpeg,jpg,png,gif,webp,pdf',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $workProgress = new WorkProgress();
        $workProgress->work_id = $request->work_id;
        $workProgress->estimated_completion_date = $request->estimated_completion_date;
        $workProgress->work_status_id = $request->work_status;
        $workProgress->mb_stages_id = $request->mb_stages;
        $workProgress->expenditure_amount = $request->expenditure_amount;
        $workProgress->status_update_date = date('Y-m-d');
        $workProgress->upload_file = store_upload($request->file, 'Work-Progress') ?? $workProgress->upload_file;
        $workProgress->description = $request->description;
        $workProgress->save();
        $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>9, 'work_stage'=>$request->mb_stages]);
        LogActivity::addToLog('Saved Work Progress','Work Progress',$workProgress->wp_id,$request->work_id);
        return redirect()->back()->with('success', 'Work-Progres Added Successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkProgress  $workProgress
     * @return \Illuminate\Http\Response
     */
    public function show(WorkProgress $workProgress)
    {
        return view('Work-Progress.show', compact('workProgress'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkProgress  $workProgress
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkProgress $workProgress)
    {
        $work_status = WorkStatus::get();
        $work_type = WorkType::get();
        $stages = WorkTypeStage::get();
        $work = Work::get();

        return view('Work-Progress.form', compact('work_type', 'stages', 'work', 'work_status', 'workProgress'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkProgress  $workProgress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkProgress $workProgress)
    {
        $validator = Validator::make($request->all(), [
            'work_id' => 'required',
            // 'estimated_completion_date' => 'required',
            'mb_stages' => 'required',
            'work_status' => 'required',
            'file' => 'nullable|mimes:jpeg,jpg,png,gif,webp,pdf',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $workProgress->work_id = $request->work_id;
        $workProgress->estimated_completion_date = $request->estimated_completion_date;
        $workProgress->work_status_id = $request->work_status;
        $workProgress->mb_stages_id = $request->mb_stages;
        $workProgress->expenditure_amount = $request->expenditure_amount;
        $workProgress->status_update_date = $request->status_update_date;
        $workProgress->upload_file = store_upload($request->file, 'Work-Progress') ?? $workProgress->upload_file;
        $workProgress->description = $request->description;
        $workProgress->save();
        LogActivity::addToLog('Update Work Progress','Work Progress',$workProgress->wp_id,$request->work_id);
        return redirect()->route('work-progress.index')->with('success', 'Work-Progress Updated Successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkProgress  $workProgress
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkProgress $workProgress)
    {
        if ($workProgress->upload_file != '' && $workProgress->upload_file != null) {
            // unlink($workProgress->file);
        }
        $workProgress->delete();
        return redirect()->route('work-progress.index')->with('success', 'Work-Progress Deleted Successfully !');
    }
}
