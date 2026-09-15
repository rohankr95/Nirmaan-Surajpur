<form method="post" action="{{ isset($scheme)?route('schemes.update',$scheme->scheme_id): route('schemes.store') }}">
    @if(isset($scheme))
        @method('PUT')
    @endif
    @csrf
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">Scheme Name :</span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="name" class="form-control" placeholder="Enter Scheme Name" value="{{ isset($scheme)?$scheme->scheme_name:"" }}" required>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">Department :</span>
        </div>
        <div class="col-sm-8">
            <select name="department" class="form-control form-select">
                <option value="">-- Select Department --</option>
                @foreach(get_departments() as $list)
                <option value="{{ $list->department_id }}" {{ echo_selected($list->department_id== (isset($scheme)?$scheme->department_id:''))}}>
                    {{ $list->department_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

<div class="form-group">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    @if(isset($scheme))
        <button type="submit" class="btn btn-primary pull-right">Update Scheme</button>
    @else
        <button type="submit" class="btn btn-success pull-right">Add Scheme</button>
    @endif
</div>
</form>
