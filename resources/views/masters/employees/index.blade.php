@extends('layout.app')
{{-- @section('title','Dashboard') --}}
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','text'=>'Dashboard'])
    <!-- Main content -->
    <div class="content">

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>
                                Employees List
                            <span class="pull-right">
                                <button class="btn btn-success" onclick="openAddModal('Add New Employee','{{ route('employee.create') }}')">Add New Employee</button>
                            </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-striped table-condensed m-0" id="dataTableExample2">
                            <thead class="bg-info">
                            <tr>
                                <th width="5%">Sn</th>
                                <th>Name</th>
                                <th>Moblile</th>
                                <th>Email</th>
                                <th>Designation</th>
                                <th>Office</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sl = 1; @endphp
                            @foreach($Employee as $list)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $list->emp_name }}</td>
                                    <td>{{ $list->emp_mobile }}</td>
                                    <td>{{ $list->emp_email }}</td>
                                    <td>{{ $list->designation->designation_name }}</td>
                                    <td>{{ $list->office->office_name }}</td>

                                    <td>
                                        <button onclick="openEditModal('Edit Employee','{{route('employee.edit', $list->emp_id )}}');" class="btn btn-primary btn-xs">
                                            <i class="ti-pencil"></i> Edit
                                        </button>
                                        <button onclick="openDeleteModal('Are you sure ?','{{route('employee.show',$list->emp_id)}}');" class="btn btn-danger btn-xs">
                                            <i class="ti-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        function addEmployeeRow(context)
        {
            $(context).closest('tbody').append(
                `<tr>
                    <td class="p-0"><input type="text" name="employee_id[]" class="form-control" placeholder="Enter Engineer Name"></td>
                    <td>
                        <button type="button" class="btn btn-xs btn-danger" onclick="removeEmployeeRow(this)"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-xs btn-success" onclick="addEmployeeRow(this)"><i class="fa fa-plus"></i> Add New</button>
                    </td>
                </tr>`);
        }
        function removeEmployeeRow(context)
        {
            $(context).closest('tr').remove();
        }
    </script>
@endsection
