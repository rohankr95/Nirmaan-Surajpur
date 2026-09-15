@extends('layout.app')
@section('title','कार्य की अंतिम स्थिति रिपोर्ट | निर्माण')
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','text'=>'Dashboard'])
    <!-- Main content -->
    <div class="content">

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>कार्य की अंतिम स्थिति</h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-condensed m-0" id="dataTableExample2">
                            <thead class="bg-info">
                                <tr>
                                    <th width="5%">क्र.</th>
                                    <th>कार्य आईडी</th>
                                    <th>कार्य नाम</th>
                                    <th>कार्य की स्थिति</th>
                                    <th>एजेंसी का नाम</th>
                                    <th>दिनांक</th>
                                    {{-- @foreach(get_work_statuses() as $status)
                                    <th>{{ $status->work_status_name }}</th>
                                    @endforeach --}}
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            @php $sl=1; @endphp
                            @foreach($work_data as $work)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td class="text-left">{{ $work->work_id }}</td>
                                    <td width="25%" class="text-left">{{ $work->work_name }}</td>
                                    <td class="text-left">{{ $work->status->work_status_name }}</td>
                                    <td width="20%" class="text-left">{{ $work->office->office_name }}</td>
                                    <td class="text-left">{{ date('d-m-Y',strtotime($work->updated_at)) }}</td>
                                    {{-- <td class="count"> {!!  ($data = $office->total_works)?'<a href="'.route("reports.works").'?agency='.$office->office_id.'">'.$data.'</a>':'-'  !!} </td> --}}
                                    {{-- @foreach(get_work_statuses() as $status)
                                        <td class="count">{!! ($data = $office->work_stage_data[$status->work_status_id])?'<a href="'.route("reports.works").'?agency='.$office->office_id.'&status='.$status->work_status_id.'">'.$data.'</a>':'-'  !!}</td>
                                    @endforeach --}}
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
