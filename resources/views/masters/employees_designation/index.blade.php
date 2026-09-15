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
                                Employees Designation List
                            <span class="pull-right">
                                <button class="btn btn-success" onclick="openAddModal('Add New Designation','{{ route('employee-designation.create') }}')">Add New Designation</button>
                            </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-striped table-condensed m-0">
                            <thead class="bg-info">
                            <tr>
                                <th width="5%">Sn</th>
                                <th>Designation Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sl = 1; @endphp
                            @foreach($designation as $list)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $list->designation_name }}</td>
                                    <td>
                                        <button onclick="openEditModal('Edit Scheme','{{route('employee-designation.edit', $list->designation_id )}}');" class="btn btn-primary btn-xs">
                                            <i class="ti-pencil"></i> Edit
                                        </button>
                                        <button onclick="openDeleteModal('Are you sure ?','{{route('employee-designation.show',$list->designation_id)}}');" class="btn btn-danger btn-xs">
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
        function addDesignationRow(context)
        {
            $(context).closest('tbody').append(
                `<tr>
                    <td class="p-0"><input type="text" name="designation_id[]" class="form-control" placeholder="Enter Designation Name"></td>
                    <td>
                        <button type="button" class="btn btn-xs btn-danger" onclick="removeEngineerRow(this)"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-xs btn-success" onclick="addEngineerRow(this)"><i class="fa fa-plus"></i> Add New</button>
                    </td>
                </tr>`);
        }
        function removeDesignationRow(context)
        {
            $(context).closest('tr').remove();
        }
    </script>
@endsection
