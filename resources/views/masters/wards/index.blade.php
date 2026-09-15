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
                            <h4>Wards List</h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0">
                            <thead class="bg-info">
                            <tr>
                                <th width="5%">Sn</th>
                                {{-- <th>Ward No</th> --}}
                                <th>Ward Name</th>
                                <th>City Name</th>
                                {{-- <th>Subdivision Name</th>
                                <th>District Name</th>
                                <th>State Name</th> --}}
                                <th width="20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sl=1; @endphp
                            @foreach($wards as $list)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    {{-- <td>{{ $list->ward_id }}</td> --}}
                                    <td>{{ $list->ward_no." - ".$list->ward_name }}</td>
                                    <td>{{ $list->city->city_name }}</td>
                                    {{-- <td>{{ $list->city->subdivision->subdivision_name }}</td>
                                    <td>{{ $list->city->subdivision->district->district_name }}</td>
                                    <td>{{ $list->city->subdivision->district->state->state_name }}</td> --}}
                                    <td>
                                        <button onclick="openEditModal('Edit ward','{{route('wards.edit', $list->ward_id )}}');" class="btn btn-primary btn-xs">
                                            <i class="ti-pencil"></i> Edit
                                        </button>
                                                                           {{-- <a href="javascript:void(0);" class="btn btn-danger btn-xs">
                                                                               <i class="ti-trash"></i> Delete
                                                                           </a> --}}
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
