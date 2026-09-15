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
                                Users List

                            <span class="pull-right">
                                <button class="btn btn-success" onclick="openAddModal('Add New User','{{ route('users.create') }}')">Add New User</button>
                            </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0">
                            <thead class="bg-info">
                            <tr>
                                <th width="5%">Sn</th>
                                <th>Login ID</th>
                                <th>User Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Office</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sl = 1; @endphp
                            @foreach($users as $list)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $list->login_id }}</td>
                                    <td>{{ $list->name }}</td>
                                    <td>{{ $list->mobile }}</td>
                                    <td>{{ $list->email }}</td>
                                    <td>{{ $list->office->office_name??'' }}</td>
                                    <td>{{ $list->office->department->department_name??'' }}</td>
                                    <td>{{ $list->role->role_name }}</td>
                                    <td>
                                        <button onclick="openEditModal('Edit User','{{route('users.edit', $list->user_id )}}');" class="btn btn-primary btn-xs">
                                            <i class="ti-pencil"></i> Edit
                                        </button>
                                        <button onclick="openDeleteModal('Are you sure ?','{{route('users.show',$list->user_id)}}');" class="btn btn-danger btn-xs">
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
