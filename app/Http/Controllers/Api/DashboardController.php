<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Same grouping the web dashboard cards use (progress vs closed vs
     * complete vs rejected), scoped to the same office/employee rules as
     * the web app -- just returned as a handful of counts instead of the
     * full status breakdown the office back-office dashboard shows.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $workBuilder = $this->scopedWorks($user);

        $total = (clone $workBuilder)->count();
        // work_status: 9 = कार्य प्रगति पर, 10 = कार्य पूर्ण, 11 = कार्य बंद, 12 = कार्य निरस्त
        $inProgress = (clone $workBuilder)->where('work_status', 9)->count();
        $complete = (clone $workBuilder)->where('work_status', 10)->count();
        $closed = (clone $workBuilder)->where('work_status', 11)->count();
        $rejected = (clone $workBuilder)->where('work_status', 12)->count();

        return response()->json([
            'user' => [
                'name' => $user->name,
                'office_name' => $user->office->office_name ?? '',
            ],
            'counts' => [
                'total' => $total,
                'in_progress' => $inProgress,
                'complete' => $complete,
                'closed' => $closed,
                'rejected' => $rejected,
            ],
        ]);
    }

    public static function scopedWorks($user)
    {
        $workBuilder = Work::query();
        if ((int) $user->user_role_id === 2) {
            $workBuilder->where('office_id', $user->office_id);
        }
        if ((int) $user->user_role_id === 3) {
            $workBuilder->where('employee_id', $user->emp_id);
        }
        return $workBuilder;
    }
}
