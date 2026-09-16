<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Work;
use App\Models\WorkDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkDocumentController extends Controller
{
    /**
     * Works the caller may attach documents to, scoped as every worklist is.
     */
    private function scopedWork($workId)
    {
        $builder = Work::query()->where('work_id', $workId);

        if (is_officer()) {
            $builder->where('office_id', session()->get('office_id'));
        }
        if (is_emp()) {
            $builder->where('employee_id', session()->get('emp_id'));
        }

        return $builder->firstOrFail();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'work_id' => 'required',
            'doc_type' => 'required|in:uc,cc,rwh,other',
            'file' => 'required|mimes:jpeg,jpg,png,gif,webp,pdf',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $work = $this->scopedWork($request->work_id);

        $document = new WorkDocument();
        $document->work_id = $work->work_id;
        $document->doc_type = $request->doc_type;
        $document->file_path = store_upload($request->file, 'Documents');
        $document->reference_no = $request->reference_no;
        $document->document_date = $request->document_date ?: null;
        $document->remark = $request->remark;
        $document->uploaded_by = session()->get('user_id');
        $document->save();

        LogActivity::addToLog('Saved Document', 'Document', $document->document_id, $work->work_id);

        return back()->with('success', WorkDocument::TYPES[$request->doc_type].' अपलोड किया गया');
    }

    public function destroy(WorkDocument $document)
    {
        $work_id = $document->work_id;
        $this->scopedWork($work_id);

        LogActivity::addToLog('Deleted Document', 'Document', $document->document_id, $work_id);
        $document->delete();

        return back()->with('success', 'दस्तावेज़ हटाया गया');
    }
}
