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
                                Schemes List

                            <span class="pull-right">
                                <button class="btn btn-success" onclick="openAddModal('Add New Scheme','{{ route('schemes.create') }}')">Add New Scheme</button>
                            </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0">
                            <thead class="bg-info">
                            <tr>
                                <th width="5%">Sn</th>
                                <th>Scheme Name</th>
                                <th>Department</th>
                                <th width="20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sl = 1; @endphp
                            @foreach($schemes as $list)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $list->scheme_name }}</td>
                                    <td>{{ $list->department->department_name }}</td>
                                    <td>
                                        <button onclick="openEditModal('Edit Scheme','{{route('schemes.edit', $list->scheme_id )}}');" class="btn btn-primary btn-xs">
                                            <i class="ti-pencil"></i> Edit
                                        </button>
                                        <button onclick="openDeleteModal('Are you sure ?','{{route('schemes.show',$list->scheme_id)}}');" class="btn btn-danger btn-xs">
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
