@extends('layout.app')
@section('title', 'प्रशासकीय  स्वीकृति | Nirmaan')
@section('content')
    @include('layout.includes.page_header', ['icon' => 'home', 'title' => 'प्रशासकीय  स्वीकृति'])
    <!-- Main content -->
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>
                                सूची
                                <span class="pull-right">
                                    <a href="{{ route('administrative-sanction.create') }}" class="btn btn-success">नया जोड़ो प्रशासकीय  स्वीकृति</a>
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0">
                            <thead class="bg-info">
                                <tr>
                                    <th>क्र.</th>
                                    <th>कार्य आईडी</th>
                                    <th>कार्य नाम</th>
                                    <th>प्रशासकीय  स्वीकृति सरकार या जिला को भेजा गया</th>
                                    <th>प्रशासकीय  स्वीकृति जमा करने की दिनांक</th>
                                    <th>प्रशासकीय  स्वीकृति स्वीकृति दिनांक</th>
                                    <th>प्रशासकीय  स्वीकृति की राशि</th> 
                                    <th>टिप्पणी</th> 
                                    <th>कारवाही</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @foreach ($administrativeSanction as $list)
                                    <tr>
                                        <td>{{ $sl++ }}</td>    
                                        <td>{{ $list->work->work_id }}</td>
                                        <td>{{ $list->work->work_name }}</td>
                                        <td>{{ $list->as_by }}</td>
                                        <td>{{ $list->submission_date }}</td>
                                        <td>{{ $list->approval_date }}</td>
                                        <td>{{ $list->as_amount }}</td>
                                        <td>{{ $list->remark }}</td>
                                        <td>
                                            <a href="{{ route('administrative-sanction.edit', $list->as_id) }}"
                                                class="btn btn-primary btn-xs" id=""><i class="ti-pencil"></i>
                                                Edit</a>

                                            <button onclick="openDeleteModal('Are You Sure You Want to Permanently Delete Record ?','{{route('administrative-sanction.show',$list->as_id)}}');" class="btn btn-danger btn-xs">
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
