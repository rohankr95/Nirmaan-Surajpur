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
                            <h4>Assembly Constituency List</h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0">
                            <thead class="bg-info">
                            <tr>
                                <th width="5%">Sn</th>
                                <th>Assembly Contituency</th>
                                <th>Parliamentary Contituency</th>
                                <th width="20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tbody>
                            @php $sl=1; @endphp
                            @foreach($assemblyConstituency as $list)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $list->assembly_constituency_name }}</td>
                                    <td>{{ $list->parliamentary_constituency ->parliamentary_constituency_name }}</td>
                                    <td>
                                        {{--                                    <a href="javascript:void(0);" class="btn btn-primary btn-xs">--}}
                                        {{--                                        <i class="ti-pencil"></i> Edit--}}
                                        {{--                                    </a>--}}
                                        {{--                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs">--}}
                                        {{--                                        <i class="ti-trash"></i> Delete--}}
                                        {{--                                    </a>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
