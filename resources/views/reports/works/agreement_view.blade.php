<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered table-striped table-hover m-0">
            <tbody>
                <tr><td>क्रमांक</td><td><b>{{ $agreement->work_order_no ?? '—' }}</b></td></tr>
                <tr><td>दिनांक</td><td><b>{{ $agreement->work_order_date ? date('d-m-Y', strtotime($agreement->work_order_date)) : '—' }}</b></td></tr>
                <tr><td>राशि</td><td><b>{{ $agreement->work_order_amount ? '₹ '.number_format($agreement->work_order_amount, 2) : '—' }}</b></td></tr>
                <tr><td>प्रारम्भ अनुमानित की दिनांक</td><td><b>{{ $work->workOrder_startDate ? date('d-m-Y', strtotime($work->workOrder_startDate)) : '—' }}</b></td></tr>
                <tr><td>समाप्ति अनुमानित की दिनांक</td><td><b>{{ $work->workOrder_endDate ? date('d-m-Y', strtotime($work->workOrder_endDate)) : '—' }}</b></td></tr>
                <tr><td>ठेकेदार / ग्रामपंचायत</td><td><b>{{ $agreement->contractor->contractor_name ?? '—' }}</b></td></tr>
                <tr><td>टिप्पणी</td><td><b>{{ $agreement->remark ?? '—' }}</b></td></tr>
                <tr><td>संलग्न फाइल</td><td>{!! $agreement->upload_file ? '<a href="'.asset($agreement->upload_file).'" target="_blank" class="btn btn-xs btn-success">देखें</a>' : '—' !!}</td></tr>
            </tbody>
        </table>
    </div>
</div>
