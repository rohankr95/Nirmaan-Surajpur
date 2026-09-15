@extends('layout.app')
@section('title', 'उपयोगकर्ता लॉगिन स्थिति| Nirmaan')
@section('content')
    @include('layout.includes.page_header', ['icon' => 'home', 'title' => 'उपयोगकर्ता लॉगिन स्थिति'])
    <!-- Main content -->
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>
                                सूची
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <div class="row">

                       
                        <form action="{{ route('reports.logs_filter') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-3 m-b-10">
                                <label>एजेंसी</label>
                                <select class="form-control form-select" name="agency">
                                    <option value="">-- Select Agency --</option>
                                    @foreach (get_offices() as $office)
                                        <option value="{{ $office->office_id }}">{{ $office->office_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label>दिनांक से</label>
                                <input type="date" name="from_date" id="" class="form-control">
                            </div>
                            <div class="col-lg-2">
                                <label for="">दिनांक तक</label>
                                <input type="date" name="to_date" id="" class="form-control">
                            </div>
                            <div class="col-lg-2">
                                <label for="">फ़िल्टर करें</label>
                                <input type="submit" value="Search" class="form-control btn btn-primary"> 
                            </div>
                        </form>
                        <form action="{{ route('reports.logs-list') }}" method="get" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-2">
                                <label for="">फ़िल्टर हटाएँ</label>
                                <input type="submit" value="Clear" class="form-control btn btn-primary"> 
                            </div>
                        </form>
                        </div>
                        <table class="table table-bordered table-hover table-striped table-sm m-0">
                            <thead class="bg-info">
                                <tr>
                                    <th>क्र.</th>
                                    <th>उपयोगकर्ता नाम</th>
                                    <th>गतिविधियाँ</th>
                                    <th>दिनांक</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @isset($logs_list)
                                @foreach ($logs_list as $list)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $list->user->name }}</td>
                                    <td>{{ $list->subject }}</td>
                                    {{-- <td>{{ $list->created_at->format('d-m-Y') }}</td> --}}
                                    <td>{{ date('d-m-Y',strtotime($list->created_at)) }}</td>
                                    {{-- <td>
                                        <a href="{{ route('technical-sanction.edit', $list->ts_id) }}"
                                            class="btn btn-primary btn-xs" id=""><i class="ti-pencil"></i>
                                            Edit</a>

                                        <button onclick="openDeleteModal('Are You Sure You Want to Permanently Delete Record ?','{{route('technical-sanction.show',$list->ts_id)}}');" class="btn btn-danger btn-xs">
                                            <i class="ti-trash"></i> Delete
                                        </button>
                                    </td> --}}
                                </tr>
                            @endforeach
                                @endisset
                                
                                @isset($logs_filter)
                                @foreach ($logs_filter as $list)
                                {{-- {{ dd($logs_filter) }} --}}
                                <tr>    
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $list->office_name }}</td>
                                    <td>{{ $list->subject }}</td>
                                    {{-- <td>{{ $list->created_at->format('d-m-Y') }}</td> --}}
                                    <td>{{ date('d-m-Y',strtotime($list->logsDate)) }}</td>
                                    {{-- <td>
                                        <a href="{{ route('technical-sanction.edit', $list->ts_id) }}"
                                            class="btn btn-primary btn-xs" id=""><i class="ti-pencil"></i>
                                            Edit</a>

                                        <button onclick="openDeleteModal('Are You Sure You Want to Permanently Delete Record ?','{{route('technical-sanction.show',$list->ts_id)}}');" class="btn btn-danger btn-xs">
                                            <i class="ti-trash"></i> Delete
                                        </button>
                                    </td> --}}
                                </tr>
                            @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
