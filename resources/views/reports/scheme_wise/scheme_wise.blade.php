@extends('layout.app')
@section('title','योजनावार रिपोर्ट | निर्माण')
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','text'=>'Dashboard'])
    <!-- Main content -->
    <div class="content">

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4> योजना  सूची </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-condensed m-0" id="dataTableExample2">
                            <thead class="bg-info">
                                <tr>
                                    <th width="5%">क्र.</th>
                                    <th>योजना का नाम</th>
                                    <th>कुल कार्य</th>
                                    @foreach(get_work_statuses() as $status)
                                    <th>{{ $status->work_status_name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            @php $sl=1; @endphp
                            @foreach($scheme_data as $scheme)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td class="text-left">{{ $scheme->scheme_name }}</td>
                                    <td class="count">{!!  ($data = $scheme->total_works)?'<a href="'.route("reports.works").'?scheme='.$scheme->scheme_id.'">'.$data.'</a>':'-' !!}</td>
                                    @foreach(get_work_statuses() as $status)
                                        <td class="count">{!! ($data = $scheme->work_stage_data[$status->work_status_id])?'<a href="'.route("reports.works").'?scheme='.$scheme->scheme_id.'&status='.$status->work_status_id.'">'.$data.'</a>':'-' !!}</td>
                                    @endforeach
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
