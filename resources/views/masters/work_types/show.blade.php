<div class="row form-group">
    <div class="col-sm-6">
        <span class="pull-right">Work Type Name :</span>
    </div>
    <div class="col-sm-6">
        <b>{{ $workType->work_type_name }}</b>
    </div>
</div>
<div class="row form-group">
    <div class="col-sm-6">
        <span class="pull-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </span>
    </div>
    <div class="col-sm-6">
        <form action="{{ route('work_types.destroy',$workType->work_type_id) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
</div>
