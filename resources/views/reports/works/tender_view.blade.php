<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered table-striped table-hover m-0">
            <tbody>
                @if(!isset($detail_page))
                <tr><td>कार्य आईडी</td><td><b>{{ $tender->work->work_id }}</b></td></tr>
                <tr><td>कार्य </td><td><b>{{ $tender->work->work_name }}</b></td></tr>
                @endif
                <tr><td>निविदा क्रमांक </td><td><b>{{ $tender->tender_no }}</b></td></tr>
                <tr><td>निविदा जारी करने की तिथि </td><td><b>{{ $tender->tender_release_date }}</b></td></tr>
                <tr><td>निविदा खोलने की तिथि </td><td><b>{{ $tender->tender_opening_date }}</b></td></tr>
                <tr><td>अनुमोदन स्थिति </td><td><b>{!! ($tender->work_order_date)?'<span class="text-success"><i class="fa fa-check m-r-2"></i>Approved</span>':'<span class="text-muted">Not Approved</span>' !!}</b></td></tr>
                @if($tender->work_order_date)
                <tr><td>कार्य आदेश की तिथि </td><td><b>{{ $tender->work_order_date }}</b></td></tr>
                @endif
                <tr><td>टिप्पणी </td><td><b>{{ $tender->remark }}</b></td></tr>
                <tr><td>संलग्न फाइल </td><td>{!! ($tender->upload_file)?'<a href="'. asset($tender->upload_file) .'" target="_blank" class="btn btn-xs btn-success">देखें</a>':'' !!}</td></tr>
            </tbody>
        </table>
    </div>
</div>
@if(!isset($detail_page))
<div class="row form-group m-t-20">
    <div class="col-sm-12 text-center">
        <span class="">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </span>
    </div>
</div>
@endif
