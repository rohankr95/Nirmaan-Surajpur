<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\AdministrativeSanction;
use App\Models\District;
use App\Models\TechnicalSanction;
use App\Models\Tender;
use App\Models\Work;
use App\Models\WorkProgress;
use App\Models\WorkProgressImage;
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
            'files' => 'required|array|min:1',
            'files.*' => 'mimes:jpeg,jpg,png,gif,webp,pdf',
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
        $workProgress->description = $request->description;
        $workProgress->save();
        $this->storeProgressImages($workProgress, $request->file('files', []));
        $work  = Work::where('work_id',$request->work_id)->update(['work_status'=>9, 'work_stage'=>$request->mb_stages]);
        LogActivity::addToLog('Saved Work Progress','Work Progress',$workProgress->wp_id,$request->work_id);
        return redirect()->back()->with('success', 'Work-Progres Added Successfully !');
    }

    /**
     * The form for adding/replacing gallery photos on an existing stage,
     * independent of recording a new status update -- for when photos were
     * never uploaded or were lost and need to be added back later.
     */
    public function galleryUploadForm(Request $request)
    {
        $work = Work::findOrFail($request->work_id);
        return view('Work-Progress.gallery_upload_form', compact('work'))->render();
    }

    /**
     * Attaches uploaded photos to the work's latest progress entry for the
     * chosen stage, creating a bare entry for that stage if none exists yet.
     * This never touches the work's own status/stage -- it is a photo-only
     * repair action, not a progress update.
     */
    public function galleryUploadStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'work_id' => 'required',
            'mb_stages' => 'required',
            'files' => 'required|array|min:1',
            'files.*' => 'mimes:jpeg,jpg,png,gif,webp,pdf',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $work = Work::findOrFail($request->work_id);

        $workProgress = WorkProgress::where('work_id', $work->work_id)
            ->where('mb_stages_id', $request->mb_stages)
            ->latest('wp_id')
            ->first();

        if (!$workProgress) {
            $workProgress = new WorkProgress();
            $workProgress->work_id = $work->work_id;
            $workProgress->mb_stages_id = $request->mb_stages;
            $workProgress->work_status_id = $work->work_status;
            $workProgress->status_update_date = date('Y-m-d');
            $workProgress->save();
        }

        $this->storeProgressImages($workProgress, $request->file('files', []));

        LogActivity::addToLog('Update Work Progress', 'Work Progress', $workProgress->wp_id, $work->work_id, 'छायाचित्र जोड़े/बदले गए');

        return back()->with('success', 'छायाचित्र सफलतापूर्वक अपलोड किए गए');
    }

    /**
     * Removes one photo from a work_progress_images entry (the multi-file
     * table). The progress entry itself is left alone even if this empties
     * it -- only the photo the user asked to remove goes away.
     */
    public function destroyImage(WorkProgressImage $workProgressImage)
    {
        $workId = $workProgressImage->workProgress->work_id;
        LogActivity::addToLog('Deleted Photo', 'Work Progress', $workProgressImage->work_progress_id, $workId);
        $workProgressImage->delete();
        return back()->with('success', 'छायाचित्र हटाया गया');
    }

    /**
     * Clears the single legacy upload_file column on an older progress
     * entry (from before work_progress_images existed).
     */
    public function destroyLegacyPhoto(WorkProgress $workProgress)
    {
        $workProgress->upload_file = null;
        $workProgress->save();
        LogActivity::addToLog('Deleted Photo', 'Work Progress', $workProgress->wp_id, $workProgress->work_id);
        return back()->with('success', 'छायाचित्र हटाया गया');
    }

    /**
     * A status update can carry several photos/bills at once; each becomes
     * its own row so the detail-page gallery can show them all.
     */
    private function storeProgressImages(WorkProgress $workProgress, array $files)
    {
        foreach ($files as $file) {
            $path = store_upload($file, 'Work-Progress');
            if ($path) {
                WorkProgressImage::create([
                    'work_progress_id' => $workProgress->wp_id,
                    'file_path' => $path,
                ]);
            }
        }
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
            'files' => 'nullable|array',
            'files.*' => 'mimes:jpeg,jpg,png,gif,webp,pdf',
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
        $workProgress->description = $request->description;
        $workProgress->save();
        $this->storeProgressImages($workProgress, $request->file('files', []));
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
