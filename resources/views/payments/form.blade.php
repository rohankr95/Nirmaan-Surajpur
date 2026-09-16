<form method="post" action="{{ route('payments.store') }}">
    @csrf
    <input type="hidden" name="work_id" value="{{ $work->work_id }}">

    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">कार्य :</span></div>
        <div class="col-sm-8"><b>{{ $work->work_name }}</b></div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">स्वीकृत राशि :</span></div>
        <div class="col-sm-8">
            <b>₹ {{ number_format((float) $work->sanction_amount, 2) }}</b>
            <small class="text-muted">| जारी: ₹ {{ number_format($work->released_amount, 2) }}
                | शेष: ₹ {{ number_format($work->balance_amount, 2) }}</small>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">राशि का प्रकार :</span></div>
        <div class="col-sm-8">
            <select name="payment_type" class="form-control form-select" required>
                @foreach(\App\Models\WorkPayment::TYPES as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">राशि (₹) :</span></div>
        <div class="col-sm-8">
            <input type="number" step="0.01" min="0.01" name="amount" class="form-control" placeholder="राशि दर्ज करें" required>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">दिनांक :</span></div>
        <div class="col-sm-8">
            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">वित्तीय वर्ष :</span></div>
        <div class="col-sm-8">
            <select name="financial_year_id" class="form-control form-select">
                @foreach($financial_years as $fy)
                    <option value="{{ $fy->id }}" {{ echo_selected($fy->id == $work->financial_year_id) }}>{{ $fy->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">किस्त क्रमांक :</span></div>
        <div class="col-sm-8">
            <input type="number" min="1" name="instalment_no" class="form-control" placeholder="जैसे 1">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">एमबी क्रमांक :</span></div>
        <div class="col-sm-4">
            <input type="text" name="mb_no" class="form-control" placeholder="एमबी क्रमांक">
        </div>
        <div class="col-sm-4">
            <input type="date" name="mb_date" class="form-control" title="एमबी दिनांक">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">टिप्पणी :</span></div>
        <div class="col-sm-8">
            <input type="text" name="remark" class="form-control" placeholder="जैसे प्रथम भुगतान जारी किया गया">
        </div>
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-danger" data-dismiss="modal">बंद करें</button>
        <button type="submit" class="btn btn-success pull-right">भुगतान दर्ज करें</button>
    </div>
</form>
