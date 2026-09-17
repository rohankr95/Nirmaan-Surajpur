<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * The "कार्य प्रगति" tab: works that have moved past sanction/tender and
     * are in the execute-and-report-progress phase. Same status set the web
     * app's WorkProgressController::index uses.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $workBuilder = DashboardController::scopedWorks($user)
            ->whereIn('work_status', [7, 8, 9])
            ->with(['work_type', 'office', 'village.grampanchayat.block', 'ward.city', 'stage']);

        $works = $workBuilder->latest('work_id')->get();

        return response()->json([
            'works' => $works->map(fn (Work $work) => $this->formatWorkSummary($work)),
        ]);
    }

    public function show(Request $request, Work $work)
    {
        $this->authorizeWork($request, $work);
        $work->load(['work_type', 'scheme', 'office', 'village.grampanchayat.block', 'ward.city', 'stage', 'employee', 'sdo']);

        return response()->json(['work' => $this->formatWorkDetail($work)]);
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

    private function formatWorkSummary(Work $work): array
    {
        return [
            'work_id' => $work->work_id,
            'work_name' => $work->work_name,
            'work_type_name' => $work->work_type->work_type_name ?? '',
            'work_status' => $work->work_status,
            'work_stage_name' => $work->stage->work_type_stage_name ?? '',
            'sanction_amount' => (float) $work->sanction_amount,
            'created_at' => optional($work->created_at)->format('d-m-Y'),
            'due_date' => $work->workComplete_endDate ? date('d-m-Y', strtotime($work->workComplete_endDate)) : null,
            'office_name' => $work->office->office_name ?? '',
            'area' => $this->areaLabel($work),
            'thumbnail' => $this->latestPhotoUrl($work),
        ];
    }

    private function formatWorkDetail(Work $work): array
    {
        $summary = $this->formatWorkSummary($work);
        return array_merge($summary, [
            'scheme_name' => $work->scheme->scheme_name ?? '',
            'department_name' => $work->department->department_name ?? '',
            'employee_name' => $work->employee->emp_name ?? '',
            'sdo_name' => $work->sdo->emp_name ?? '',
            'latitude' => $work->latitude,
            'longitude' => $work->longitude,
            'total_stages' => (int) $work->stages,
        ]);
    }

    private function areaLabel(Work $work): string
    {
        if ($work->village) {
            $gp = $work->village->grampanchayat->grampanchayat_name ?? '';
            $block = $work->village->grampanchayat->block->block_name ?? '';
            return trim($gp . ($block ? ' / ' . $block : ''));
        }
        if ($work->ward) {
            $city = $work->ward->city->city_name ?? '';
            return trim(($work->ward->ward_name ?? '') . ($city ? ' / ' . $city : ''));
        }
        return '';
    }

    private function latestPhotoUrl(Work $work): ?string
    {
        $latest = $work->work_progress()
            ->with('images')
            ->latest('wp_id')
            ->get()
            ->first(fn ($wp) => $wp->images->isNotEmpty() || $wp->upload_file);

        if (!$latest) {
            return null;
        }
        $path = $latest->images->first()->file_path ?? $latest->upload_file;
        return $path ? asset($path) : null;
    }
}
