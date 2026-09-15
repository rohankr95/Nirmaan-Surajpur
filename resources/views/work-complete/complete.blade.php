<form action="{{ route('work-complete') }}" method="post" enctype="multipart/form-data" id="wpCompleteForm">
    @csrf
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="work_completion_date" class="col-form-label">कार्य पूर्ण होने की तिथि <span
                    style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="date" id="work_completion_date" name="work_completion_date" required>
            <input type="hidden" name="work_id" id="work_id" value="{{ $work->work_id
            }}">
           <input type="hidden" name="work_status" id="work_status" value="{{ $work_status->work_status_id }}">
        </div>
        <div class="col-sm-4">
            <label for="file" class="col-form-label">दस्तावेज अपलोड करें<span
                style="color: red">&nbsp;*</span><span
                style="color: red">&nbsp; (Images/Pdf)</span></label>
            <input class="form-control" type="file" id="file" name="file" accept=".png, .jpg, .jpeg, .pdf" required/>
        </div>
        <div class="col-sm-4">
            <label for="remark" class="col-form-label">टिप्पणी </label>
            <textarea name="remark" id="remark" cols="20" rows="1" class="form-control"></textarea>
        </div>
    </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <input type="submit" id="submit" class="btn btn-lg btn-success pull-right" value="कार्य पूर्ण करें ">
            </div>
        </div>
</form>