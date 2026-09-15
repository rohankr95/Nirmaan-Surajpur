@extends('layout.app')
@section('title','एजेंसीवार फ़ाइल अपलोड स्थिति रिपोर्ट | निर्माण')
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','text'=>'Dashboard'])
    <!-- Main content -->
    <div class="content">

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4> एजेंसीवार सूची </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-condensed m-0" id="dataTableExample2">
                            <thead class="bg-info">
                                <tr>
                                    <th width="5%">क्र.</th>
                                    <th>एजेंसी का नाम</th>
                                    <th>कुल कार्य</th>
                                    @foreach(get_work_statuses() as $status)
                                    @if ($status->work_status_id != 1 && $status->work_status_id != 11 && $status->work_status_id != 12)
                                    <th>{{ $status->work_status_name }}</th>
                                    @continue
                                    @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            @php $sl=1; @endphp
                            @foreach($office_data as $office)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td class="text-left">{{ $office->office_name }}</td>
                                    <td class="count"> {!!  ($data = $office->total_works)?'<a href="'.route("reports.works").'?agency='.$office->office_id.'">'.$data.'</a>':'-'  !!} </td>
                                    @foreach(get_work_statuses() as $status)
                                        @if ($status->work_status_id != 1 && $status->work_status_id != 11 && $status->work_status_id != 12)
                                        <td class="count">{!! ($data = $office->work_stage_data[$status->work_status_id])?'<a href="'.route("reports.works").'?agency='.$office->office_id.'&status='.$status->work_status_id.'">'.$data.'</a>':'-'  !!}</td>
                                        @continue
                                        @endif
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
