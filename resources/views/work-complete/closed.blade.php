<form action="{{ route('work-closed') }}" method="post" enctype="multipart/form-data" id="wpClosedForm">
    @csrf
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="agreement_date" class="col-form-label">कार्य बंद होने की तिथि <span
                    style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="date" id="close_date" name="close_date" required>
            <input type="hidden" name="work_id" id="work_id" value="{{ $work->work_id
            }}">
           <input type="hidden" name="work_status" id="work_status" value="12">
        </div>
        <div class="col-sm-8">
            <label for="remark" class="col-form-label">टिप्पणी </label>
            <textarea name="remark" id="remark" cols="20" rows="1" class="form-control"></textarea>
        </div>
    </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <input type="submit" id="submit" class="btn btn-lg btn-danger pull-right" value="कार्य बंद करें">
            </div>
        </div>
</form>