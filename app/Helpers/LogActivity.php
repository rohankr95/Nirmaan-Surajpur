<?php


namespace App\Helpers;
use Illuminate\Support\Facades\Request;
use App\Models\LogActivity as LogActivityModel;


class LogActivity
{
    /**
     * Record an action against the record it happened to.
     *
     * @param  string      $subject  what happened, e.g. 'Saved Work'
     * @param  string      $module   the kind of record, e.g. 'Work', 'TS'
     * @param  int|null    $id       the record's own id
     * @param  int|null    $workId   the work this belongs to, where there is one
     * @param  string|null $details  human-readable specifics, e.g. an amount or a date
     */
    public static function addToLog($subject, $module, $id = null, $workId = null, $details = null)
    {
    	$log = [];
    	$log['subject'] = $subject;
    	$log['details'] = $details;
    	$log['subject_type'] = $module;
    	$log['subject_id'] = $id;
    	$log['work_id'] = $workId;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
    	$log['module'] = $module;
    	// Session carries the web login; a token (mobile/API) request has no
    	// session, so fall back to whichever user the request authenticated as.
    	$log['user_id'] = session()->get('user_id') ?? \request()->user()?->user_id;
    	LogActivityModel::create($log);
    }

    public static function logActivityLists()
    {
    	return LogActivityModel::latest()->get();
    }


}
