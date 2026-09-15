<div class="row form-group">
  <div class="col-sm-6">
      <span class="pull-right">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </span>
  </div>
  <div class="col-sm-6">
      <form action="{{ route('tender.destroy', $tender->tender_id) }}" method="post">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
      </form>
  </div>
</div>
