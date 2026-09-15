<div class="row">
    <div class="col-sm-6">
        <span class="pull-right">Scheme Name :</span>
    </div>
    <div class="col-sm-6">
        <b>{{ $scheme->scheme_name }}</b>
    </div>
</div>
<div class="row form-group">
    <div class="col-sm-6">
        <span class="pull-right">Department Name :</span>
    </div>
    <div class="col-sm-6">
        <b>{{ $scheme->department->department_name }}</b>
    </div>
</div>
<div class="row form-group">
    <div class="col-sm-6">
        <span class="pull-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </span>
    </div>
    <div class="col-sm-6">
        <form action="{{ route('schemes.destroy',$scheme->scheme_id) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
</div> 
