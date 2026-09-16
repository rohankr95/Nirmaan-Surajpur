<div class="row form-group">
    <div class="col-sm-12">
        <b>{{ $work->work_name }}</b>
    </div>
</div>

<div class="row form-group">
    <div class="col-sm-3">
        <div class="panel panel-default"><div class="panel-body text-center p-0">
            <b>₹ {{ number_format((float) $work->sanction_amount, 2) }}</b><br><small>स्वीकृत</small>
        </div></div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-default"><div class="panel-body text-center p-0">
            <b class="text-success">₹ {{ number_format($work->released_amount, 2) }}</b><br><small>जारी (जिला द्वारा)</small>
        </div></div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-default"><div class="panel-body text-center p-0">
            <b class="text-danger">₹ {{ number_format($work->expenditure_amount, 2) }}</b><br><small>व्यय (विभाग द्वारा)</small>
        </div></div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-default"><div class="panel-body text-center p-0">
            <b>₹ {{ number_format($work->evaluation_amount, 2) }}</b><br><small>मूल्यांकन (इंजीनियर द्वारा)</small>
        </div></div>
    </div>
</div>

<table class="table table-bordered table-condensed table-striped">
    <thead class="bg-info">
    <tr>
        <th width="5%">क्र.</th>
        <th>दिनांक</th>
        <th>वित्तीय वर्ष</th>
        <th class="text-right">राशि</th>
        <th>राशि के प्रकार</th>
        <th>किस्त</th>
        <th>एमबी</th>
        <th>रिमार्क</th>
        <th>दर्ज करने वाला</th>
        @if(is_admin())<th></th>@endif
    </tr>
    </thead>
    <tbody>
    @php $sl = 1; @endphp
    @forelse($payments as $payment)
        <tr>
            <td>{{ $sl++ }}</td>
            <td>{{ optional($payment->payment_date)->format('d-m-Y') }}</td>
            <td>{{ $payment->financial_year->name ?? '—' }}</td>
            <td class="text-right">{{ number_format((float) $payment->amount, 2) }}</td>
            <td>{{ $payment->type_label }}</td>
            <td>{{ $payment->instalment_no ?? '—' }}</td>
            <td>{{ $payment->mb_no ?? '—' }}</td>
            <td>{{ $payment->remark ?? '—' }}</td>
            <td>{{ $payment->creator->name ?? '—' }}</td>
            @if(is_admin())
                <td>
                    <form method="post" action="{{ route('payments.destroy', $payment->payment_id) }}"
                          onsubmit="return confirm('यह भुगतान प्रविष्टि हटाएं?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-xs"><i class="ti-trash"></i></button>
                    </form>
                </td>
            @endif
        </tr>
    @empty
        <tr><td colspan="10" class="text-center">इस कार्य के लिए कोई भुगतान प्रविष्टि नहीं है</td></tr>
    @endforelse
    </tbody>
</table>

<div class="form-group">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">बंद करें</button>
</div>
