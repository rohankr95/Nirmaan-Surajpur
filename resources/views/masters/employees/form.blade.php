<form method="post"
    action="{{ isset($Employee) ? route('employee.update', $Employee->emp_id) : route('employee.store') }}">
    @if (isset($Employee))
        @method('PUT')
    @endif
    @csrf
    @if (is_admin())
        <div class="row form-group">
            <div class="col-sm-4">
                <span class="pull-right">Agency :</span>
            </div>
            <div class="col-sm-8">
                <select name="office_id" class="form-control form-select">
                    <option value="">-- Select Agency --</option>
                    @foreach (get_offices() as $list)
                        <option value="{{ $list->office_id }}"
                            {{ echo_selected($list->office_id == (isset($Employee) ? $Employee->office_id : '')) }}>
                            {{ $list->office_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">Designation :</span>
        </div>
        <div class="col-sm-8">
            <select name="designation_id" class="form-control form-select">
                <option value="">-- Select Designation --</option>
                @foreach (get_designation() as $list)
                    <option value="{{ $list->designation_id }}"
                        {{ echo_selected($list->designation_id == (isset($Employee) ? $Employee->emp_designation_id : '')) }}>
                        {{ $list->designation_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row form-group">
        <table class="table table-bordered p-0 m-0">
            <thead>
                @if (isset($Employee))
                    <tr>
                        <th>Employee Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                    </tr>
                @else
                    <tr>
                        <th>Employee Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                    </tr>
                @endif

            </thead>
            <tbody>
                @if (isset($Employee))
                    {{-- @foreach ($engineers as $list) --}}
                    <tr>
                        <td class="p-0">
                            <input type="text" name="name" class="form-control" placeholder="Enter Employee Name"
                                value="{{ $Employee->emp_name }}">
                        </td>
                        <td class="p-0">
                            <input type="number" name="mobile" maxlength="12" class="form-control"
                                placeholder="Enter Mobile" value="{{ $Employee->emp_mobile }}">
                        </td>
                        <td class="p-0">
                            <input type="email" name="email" class="form-control" placeholder="Enter Email"
                                value="{{ $Employee->emp_email }}">
                        </td>
                        {{-- <td>
                            <button type="button" class="btn btn-xs btn-danger" onclick="removeEmployeeRow(this)"><i
                                    class="fa fa-trash"></i></button>
                            <button type="button" class="btn btn-xs btn-success" onclick="addEmployeeRow(this)"><i
                                    class="fa fa-plus"></i> Add New</button>
                        </td> --}}
                    </tr>
                    {{-- @endforeach --}}
                @else
                    <tr>
                        <td class="p-0"><input type="text" name="name" class="form-control"
                                placeholder="Enter Employee Name"></td>
                        <td class="p-0"><input type="number" name="mobile" maxlength="10" class="form-control"
                                placeholder="Enter Mobile"></td>
                        <td class="p-0"><input type="email" name="email" class="form-control"
                                placeholder="Enter Email"></td>
                        {{-- <td>
                            <button type="button" class="btn btn-xs btn-danger" onclick="removeEmployeeRow(this)"><i
                                    class="fa fa-trash"></i></button>
                            <button type="button" class="btn btn-xs btn-success" onclick="addEmployeeRow(this)"><i
                                    class="fa fa-plus"></i> Add New</button>
                        </td> --}}
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        @if (isset($Employee))
            <button type="submit" class="btn btn-primary pull-right">Update Designation</button>
        @else
            <button type="submit" class="btn btn-success pull-right">Add Designation</button>
        @endif
    </div>
</form>
