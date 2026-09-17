<?php

namespace App\Http\Controllers\Api;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\Work;
use App\Models\WorkProgress;
use App\Models\WorkProgressImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkProgressController extends Controller
{
    /**
     * The "कार्य प्रगति चरण" screen: every stage this work's work_type
     * defines, each showing its own latest progress entry (percent complete
     * derived from stage_number / work.stages, same figures the reference
     * screenshots show) -- same source data as the web app's
     * reports/works/progress_view.blade.php timeline.
     */
    public function stages(Request $request, Work $work)
    {
        $this->authorizeWork($request, $work);
        $work->load('work_type.work_stages');
        $totalStages = (int) $work->stages ?: $work->work_type->work_stages->count();

        $stages = $work->work_type->work_stages
            ->sortBy('stage_number')
            ->values()
            ->map(function ($stage) use ($work, $totalStages) {
                $current = WorkProgress::where('work_id', $work->work_id)
                    ->where('mb_stages_id', $stage->work_type_stage_id)
                    ->with('images')
                    ->latest('wp_id')
                    ->first();

                return [
                    'work_type_stage_id' => $stage->work_type_stage_id,
                    'stage_number' => $stage->stage_number,
                    'stage_name' => $stage->work_type_stage_name,
                    'percent_complete' => $totalStages ? (int) round($stage->stage_number / $totalStages * 100) : null,
                    'recorded' => (bool) $current,
                    'estimated_completion_date' => $current?->estimated_completion_date,
                    'expenditure_amount' => $current ? (float) $current->expenditure_amount : null,
                    'description' => $current?->description,
                    'status_update_date' => $current?->status_update_date,
                    'photos' => $current
                        ? $current->images->map(fn ($img) => asset($img->file_path))->push($current->upload_file ? asset($current->upload_file) : null)->filter()->values()
                        : [],
                ];
            });

        return response()->json(['work_id' => $work->work_id, 'total_stages' => $totalStages, 'stages' => $stages]);
    }

    /**
     * Record a progress update for one stage: percent complete/dates/amount
     * are unchanged design, but photos are required (min 1) same as the web
     * form, and an optional device GPS fix updates the work's location --
     * works.latitude/longitude already exist for the map view feature, so a
     * field visit's geo-tagged photo doubles as confirming the work site.
     */
    public function store(Request $request, Work $work)
    {
        $this->authorizeWork($request, $work);

        $validator = Validator::make($request->all(), [
            'mb_stages' => 'required|integer',
            'estimated_completion_date' => 'nullable|date',
            'expenditure_amount' => 'nullable|numeric',
            'description' => 'nullable|string',
            'files' => 'required|array|min:1',
            'files.*' => 'mimes:jpeg,jpg,png,gif,webp,pdf',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $workProgress = new WorkProgress();
        $workProgress->work_id = $work->work_id;
        $workProgress->estimated_completion_date = $request->estimated_completion_date;
        $workProgress->work_status_id = 9;
        $workProgress->mb_stages_id = $request->mb_stages;
        $workProgress->expenditure_amount = $request->expenditure_amount;
        $workProgress->status_update_date = date('Y-m-d');
        $workProgress->description = $request->description;
        $workProgress->save();

        foreach ($request->file('files', []) as $file) {
            $path = store_upload($file, 'Work-Progress');
            if ($path) {
                WorkProgressImage::create(['work_progress_id' => $workProgress->wp_id, 'file_path' => $path]);
            }
        }

        $work->work_status = 9;
        $work->work_stage = $request->mb_stages;
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $work->latitude = $request->latitude;
            $work->longitude = $request->longitude;
        }
        $work->save();

        LogActivity::addToLog('Saved Work Progress (Mobile)', 'Work Progress', $workProgress->wp_id, $work->work_id);

        return response()->json(['message' => 'प्रगति सफलतापूर्वक दर्ज की गई', 'wp_id' => $workProgress->wp_id]);
    }

    private function authorizeWork(Request $request, Work $work): void
    {
        $user = $request->user();
        abort_unless(
            DashboardController::scopedWorks($user)->where('work_id', $work->work_id)->exists(),
            403,
            'आपको इस कार्य को देखने की अनुमति नहीं है'
        );
    }
}
