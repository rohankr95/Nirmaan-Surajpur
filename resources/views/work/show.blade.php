<div class="row form-group">
  <div class="col-sm-6">
      <span class="pull-right">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">रद्द करें</button>
      </span>
  </div>
  <div class="col-sm-6">
      <form action="{{ route('work.destroy', $work->work_id) }}" method="post">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">कार्य हटाये</button>
      </form>
  </div>
</div>
