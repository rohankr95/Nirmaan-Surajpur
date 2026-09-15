<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered table-striped table-hover m-0">
            <tbody>
                @if(!isset($detail_page))
                <tr><td>कार्य आईडी</td><td><b>{{ $technicalSanction->work->work_id }}</b></td></tr>
                <tr><td>कार्य </td><td><b>{{ $technicalSanction->work->work_name }}</b></td></tr>
                @endif
                <tr><td>तकनीकी स्वीकृति क्रमांक </td><td><b>{{ $technicalSanction->ts_no }}</b></td></tr>
                <tr><td>तकनीकी स्वीकृति जमा करने की तिथि </td><td><b>{{ $technicalSanction->submission_date }}</b></td></tr>
                <tr><td>तकनीकी स्वीकृति राशि </td><td><b>{{ $technicalSanction->ts_amount }}</b></td></tr>
                <tr><td>अनुमोदन स्थिति </td><td><b>{!! ($technicalSanction->approval_date)?'<span class="text-success"><i class="fa fa-check m-r-2"></i>Approved</span>':'<span class="text-muted">Not Approved</span>' !!}</b></td></tr>
                @if($technicalSanction->approval_date)
                <tr><td>तकनीकी स्वीकृति अनुमोदन तिथि </td><td><b>{{ $technicalSanction->approval_date }}</b></td></tr>
                @endif
                <tr><td>टिप्पणी </td><td><b>{{ $technicalSanction->remark }}</b></td></tr>
                <tr><td>संलग्न फाइल </td><td>{!! ($technicalSanction->upload_file)?'<a href="'. asset($technicalSanction->upload_file) .'" target="_blank" class="btn btn-xs btn-success btn-block">देखें</a>':'' !!}</td></tr>
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
