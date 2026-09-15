@extends('layout.app')
{{-- @section('title', 'Dashboard') --}}
@section('content')
    @include('layout.includes.page_header', ['icon' => 'home', 'text' => 'Dashboard'])
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
                                    <a href="{{ route('work-progress.create') }}" class="btn btn-success">  नया जोड़ें कार्य प्रगति</a>
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0">
                            <thead class="bg-info">
                                <tr>
                                    <th>S.no</th>
                                    <th>Work Id</th>
                                    <th>Work Name</th>
                                    <th>Work Status</th>
                                    <th>MB Stages</th>
                                    <th>Status update date</th>
                                    <th>Estimated Completion date</th>
                                    <th>Expenditure amount</th> 
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @foreach ($workProgress as $list)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>{{ $list->work->work_id }}</td>
                                        <td>{{ $list->work->work_name }}</td>
                                        <td>{{ $list->workStatus->work_status_name }}</td>
                                        <td>{{ $list->workTypeStage->work_type_stage_name }}</td>
                                        <td>{{ $list->status_update_date }}</td>
                                        <td>{{ $list->estimated_completion_date }}</td>
                                        <td>{{ $list->expenditure_amount }}</td>
                                        <td>{{ $list->description }}</td>
                                        <td>
                                            <a href="{{ route('work-progress.edit', $list->wp_id) }}"
                                                class="btn btn-primary btn-xs" id=""><i class="ti-pencil"></i>
                                                Edit</a>

                                            <button onclick="openDeleteModal('Are You Sure You Want to Permanently Delete Record ?','{{route('work-progress.show',$list->wp_id)}}');" class="btn btn-danger btn-xs">
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
