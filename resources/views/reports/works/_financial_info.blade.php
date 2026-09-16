@php
    $payments = $work->payments->sortByDesc('payment_date');
@endphp
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title"><h4>वित्तीय जानकारी</h4></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-6 col-sm-4 col-md-2">
                <div class="panel panel-primary"><div class="panel-body text-center">
                    <div class="small">कुल राशि<br>(AS Amount)</div>
                    <h4 class="m-0">₹ {{ number_format((float) $work->sanction_amount, 2) }}</h4>
                </div></div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <div class="panel panel-success"><div class="panel-body text-center">
                    <div class="small">जारी राशि<br>(जिला द्वारा)</div>
                    <h4 class="m-0">₹ {{ number_format($work->released_amount, 2) }}</h4>
                </div></div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <div class="panel panel-danger"><div class="panel-body text-center">
                    <div class="small">व्यय राशि<br>(विभाग द्वारा)</div>
                    <h4 class="m-0">₹ {{ number_format($work->expenditure_amount, 2) }}</h4>
                </div></div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <div class="panel panel-warning"><div class="panel-body text-center">
                    <div class="small">मूल्यांकन राशि<br>(इंजीनियर द्वारा)</div>
                    <h4 class="m-0">₹ {{ number_format($work->evaluation_amount, 2) }}</h4>
                </div></div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <div class="panel panel-info"><div class="panel-body text-center">
                    <div class="small">शेष राशि<br>(जिला से)</div>
                    <h4 class="m-0">₹ {{ number_format($work->balance_amount, 2) }}</h4>
                </div></div>
            </div>
        </div>

        <table class="table table-bordered table-condensed table-striped m-0">
            <thead class="bg-info">
                <tr>
                    <th width="5%">क्र.</th>
                    <th>दिनांक</th>
                    <th>वित्तीय वर्ष</th>
                    <th class="text-right">राशि</th>
                    <th>राशि के प्रकार</th>
                    <th>रिमार्क</th>
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
                        <td>{{ $payment->remark ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">इस कार्य के लिए कोई भुगतान प्रविष्टि नहीं है</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
