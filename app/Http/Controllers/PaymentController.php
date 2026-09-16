<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\FinancialYear;
use App\Models\Work;
use App\Models\WorkPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * The works a user may see money for, scoped the same way every other
     * worklist in the app is scoped.
     */
    private function scopedWorks()
    {
        $builder = Work::query()->with('payments');

        if (is_officer()) {
            $builder->where('office_id', session()->get('office_id'));
        }
        if (is_emp()) {
            $builder->where('employee_id', session()->get('emp_id'));
        }

        return $builder;
    }

    public function index(Request $request)
    {
        $builder = $this->scopedWorks();

        if ($request->filled('financial_year')) {
            $builder->where('financial_year_id', $request->financial_year);
        }
        if ($request->filled('scheme')) {
            $builder->where('scheme_id', $request->scheme);
        }

        $work_data = $builder->latest()->get();
        $financial_years = FinancialYear::all();

        return view('payments.index', compact('work_data', 'financial_years', 'request'));
    }

    /**
     * The add-payment form for one work, rendered into the shared modal.
     */
    public function create(Request $request)
    {
        $work = $this->scopedWorks()->where('work_id', $request->work_id)->firstOrFail();
        $financial_years = FinancialYear::all();

        return view('payments.form', compact('work', 'financial_years'))->render();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'work_id' => 'required',
            'payment_type' => 'required|in:released,expenditure,evaluation',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Scope check: a user must not record money against a work they
        // cannot otherwise see.
        $work = $this->scopedWorks()->where('work_id', $request->work_id)->firstOrFail();

        $payment = new WorkPayment();
        $payment->work_id = $work->work_id;
        $payment->payment_type = $request->payment_type;
        $payment->amount = $request->amount;
        $payment->payment_date = $request->payment_date;
        $payment->financial_year_id = $request->financial_year_id ?: $work->financial_year_id;
        $payment->instalment_no = $request->instalment_no;
        $payment->mb_no = $request->mb_no;
        $payment->mb_date = $request->mb_date;
        $payment->remark = $request->remark;
        $payment->created_by = session()->get('user_id');
        $payment->save();

        $details = $payment->type_label . ': ₹' . number_format($payment->amount, 2)
            . ' (दिनांक ' . $payment->payment_date->format('d-m-Y') . ')';
        LogActivity::addToLog('Saved Payment', 'Payment', $payment->payment_id, $work->work_id, $details);

        return back()->with('success', 'भुगतान दर्ज किया गया');
    }

    /**
     * The ledger for one work, rendered into the shared modal.
     */
    public function history(Request $request)
    {
        $work = $this->scopedWorks()->where('work_id', $request->work_id)->firstOrFail();
        $payments = $work->payments()->with('creator')->orderBy('payment_date')->orderBy('payment_id')->get();

        return view('payments.history', compact('work', 'payments'))->render();
    }

    public function destroy(WorkPayment $payment)
    {
        $work_id = $payment->work_id;
        // Only an admin may remove a recorded payment; for everyone else the
        // ledger is append-only.
        if (! is_admin()) {
            return back()->with('error', 'भुगतान प्रविष्टि हटाने की अनुमति नहीं है');
        }

        LogActivity::addToLog('Deleted Payment', 'Payment', $payment->payment_id, $work_id);
        $payment->delete();

        return back()->with('success', 'भुगतान प्रविष्टि हटाई गई');
    }
}
