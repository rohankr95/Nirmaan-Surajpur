@extends('layout.app')
@section('title','एजेंसीवार रिपोर्ट | निर्माण')
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','text'=>'Dashboard'])
    <!-- Main content -->
    <div class="content">

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4> एजेंसीवार  सूची </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-condensed m-0" id="dataTableExample2">
                            <thead class="bg-info">
                                <tr>
                                    <th width="5%">क्र.</th>
                                    <th>एजेंसी का नाम</th>
                                    <th>कुल कार्य</th>
                                    <th>तकनीकी स्वीकृति</th>  
                                    <th>प्रशासकीय  स्वीकृति</th>
                                    <th>निविदा</th>
                                    <th>कार्य प्रगति</th>
                                    <th>कार्य पूर्ण </th>
                                    <th>--</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            @php $sl=1; @endphp
                            @foreach($office_data as $office)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td class="text-left">{{ $office->office_name }}</td>
                                    <td class="count"> {{ $office->total_works }} </td>
                                    <td class="count">{{ $office->work_stage_data['administrative_sanctions'] }}</td>
                                    <td class="count">{{ $office->work_stage_data['technical_sanctions'] }}</td>
                                    <td class="count">{{ $office->work_stage_data['tenders'] }}</td>
                                    <td class="count">{{ $office->work_stage_data['work_progress'] }}</td>
                                    <td class="count">{{ $office->work_stage_data['work_completes'] }}</td>
                                    <td>--</td>
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
