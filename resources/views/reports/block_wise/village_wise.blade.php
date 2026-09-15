@extends('layout.app')
@section('title','ग्राम रिपोर्ट | निर्माण')
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','text'=>'Dashboard'])
    <!-- Main content -->
    <div class="content">

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4> ग्राम सूची </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-condensed text-center m-0" id="dataTableExample2">
                            <thead class="bg-info">
                                <tr>
                                    <th width="5%">क्र.</th>
                                    <th class="text-left">ग्राम का नाम</th>
                                    <th>कुल कार्य</th>
                                    @foreach(get_work_statuses() as $status)
                                    <th>{{ $status->work_status_name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            @php $sl=1; @endphp
                            @foreach($village_data as $village)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td class="text-left">{{ $village->village_name }}</td>
                                    <td class="count"> {!!  ($data = $village->total_works)?'<a href="'.route("reports.works").'?village='.$village->village_id.'">'.$data.'</a>':'-'  !!}</td>
                                    @foreach(get_work_statuses() as $status)
                                        <td class="count">{!!  ($data = $village->work_stage_data[$status->work_status_id])?'<a href="'.route("reports.works").'?agency='.$village->village_id.'&status='.$status->work_status_id.'">'.$data.'</a>':'-'  !!}</td>
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
