<form method="post"
    action="{{ isset($EmployeeDesignation) ? route('employee-designation.update', $EmployeeDesignation->designation_id) : route('employee-designation.store') }}">
    @if (isset($EmployeeDesignation))
        @method('PUT')
    @endif
    @csrf
    <div class="row form-group">
        <table class="table table-bordered p-0 m-0">
            <thead>
                @if (isset($EmployeeDesignation))
                    <tr>
                    <th>Designation Name</th> 
                </tr>
                @else
                <tr>
                    <th>Designation Name</th> 
                    <th>Action</th>
                </tr>
                @endif
               
            </thead>
            <tbody>
                @if (isset($EmployeeDesignation))
                    {{-- @foreach ($engineers as $list) --}}
                        <tr>
                            <td class="p-0">
                                {{-- <input type="hidden" name="office_id[]" value="{{ $engineer->office_id }}"> --}}
                                <input type="text" name="designation_name" class="form-control"
                                    placeholder="Enter Designation Name" value="{{ $EmployeeDesignation->designation_name }}">
                            </td>
                            {{-- <td>
                                <button type="button" class="btn btn-xs btn-danger" onclick="removeStageRow(this)"><i
                                        class="fa fa-trash"></i></button>
                                <button type="button" class="btn btn-xs btn-success" onclick="addStageRow(this)"><i
                                        class="fa fa-plus"></i> Add New</button>
                            </td> --}}
                        </tr>
                    {{-- @endforeach --}}
                @else
                    <tr>
                        <td class="p-0"><input type="text" name="designation_id[]" class="form-control"
                                placeholder="Enter Designation Name"></td>
                        <td>
                            <button type="button" class="btn btn-xs btn-danger" onclick="removeDesignationRow(this)"><i
                                    class="fa fa-trash"></i></button>
                            <button type="button" class="btn btn-xs btn-success" onclick="addDesignationRow(this)"><i
                                    class="fa fa-plus"></i> Add New</button>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        @if (isset($EmployeeDesignation))
            <button type="submit" class="btn btn-primary pull-right">Update Designation</button>
        @else
            <button type="submit" class="btn btn-success pull-right">Add Designation</button>
        @endif
    </div>
</form>
