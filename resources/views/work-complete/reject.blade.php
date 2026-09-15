<form action="{{ route('work-reject') }}" method="post" enctype="multipart/form-data" id="wpRejectForm">
  @csrf
  <div class="form-group row">
      <div class="col-sm-4">
          <label for="work_rejection_date" class="col-form-label">कार्य अस्वीकृति तिथि <span
                  style="color: red">&nbsp;*</span></label>
          <input class="form-control" type="date" id="work_rejection_date" name="work_rejection_date" required>
          <input type="hidden" name="work_id" id="work_id" value="{{ $work->work_id
          }}">
         <input type="hidden" name="work_status" id="work_status" value="{{ 11 }}">
      </div>
      <div class="col-sm-8">
          <label for="remark" class="col-form-label">टिप्पणी </label>
          <textarea name="remark" id="remark"  cols="20" rows="1" class="form-control"></textarea>
      </div>
  </div>
      <div class="form-group row">
          <div class="col-sm-12">
              <input type="submit" id="submit" class="btn btn-lg btn-danger pull-right" value="कार्य निरस्त करें ">
          </div>
      </div>
</form> 
