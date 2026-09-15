@extends('layout.app')
@section('title', 'Dashboard')
@section('content')
    @include('layout.includes.page_header', ['icon' => 'home', 'title' => 'Dashboard'])
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
                                    <a href="{{ route('tender.create') }}" class="btn btn-success">Add New Tender</a>
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
                                    <th>निविदा सं.</th>
                                    <th>निविदा रिलीज़ दिनांक</th>
                                    <th>निविदा खुलने की दिनांक</th>
                                    <th>वर्क ऑर्डर की दिनांक</th>
                                    <th>टिप्पणी</th> 
                                    <th>कारवाही</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @foreach ($tender as $list)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>{{ $list->work->work_id }}</td>
                                        <td>{{ $list->work->work_name }}</td>
                                        <td>{{ $list->tender_no }}</td>
                                        <td>{{ $list->tender_release_date }}</td>
                                        <td>{{ $list->tender_opening_date }}</td>
                                        <td>{{ $list->work_order_date }}</td>
                                        <td>{{ $list->remark }}</td>
                                        <td>
                                            <a href="{{ route('tender.edit',$list->tender_id) }}"
                                                class="btn btn-primary btn-xs" id=""><i class="ti-pencil"></i>
                                                Edit</a>

                                            <button onclick="openDeleteModal('Are You Sure You Want to Permanently Delete Record ?','{{ route('tender.show',$list->tender_id)}}');" class="btn btn-danger btn-xs">
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
