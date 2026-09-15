@extends('layout.app')
{{-- @section('title', 'Dashboard') --}}
@section('content')
    @include('layout.includes.page_header', ['icon' => 'home', 'text' => 'Dashboard'])
    <!-- Main content -->
    <div class="content">

        <div class="row">

            <div class="col-xs-6 col-sm-6 col-md-3 col-lg-2" style="padding: 4px">
                <div class="panel panel-bd m-0">
                    <div class="panel-body bg-success" style="padding: 10px">
                        <a href="#">
                            <div class="statistic-box">
                                <h2>
                                    <a href="{{ route('reports.works') }}"> <span
                                            class="count-number text-black">{{ $status_total_works }}</span></a>
                                    <span class="pull-right text-black"><i class="ti-bar-chart"></i></span>
                                </h2>
                                <div class="small m-0">दर्ज कार्य</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


            @foreach (get_work_statuses() as $status)
                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-2" style="padding: 4px">
                    <div class="panel panel-bd m-0">
                        <div class="panel-body bg-success" style="padding: 10px">
                            <a href="#">
                                <div class="statistic-box">
                                    <h2>
                                        <a href="{{ route('reports.works') }}?status={{ $status->work_status_id }}"> <span
                                                class="count-number text-black">{{ $status_data[$status->work_status_id] }}</span></a>
                                        <span class="pull-right text-black"><i class="ti-bar-chart"></i></span>
                                    </h2>
                                    <div class="small m-0">{{ $status->work_status_name }}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row m-t-20">
            <div class="col-lg-12">
                <div class="panel panel-success lobidisable">
                    <div class="panel-heading">
                        <h4 class="panel-title">वित्तीय वर्ष के आँकड़े</h4>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-striped table-bordered table-condensed text-center m-0">
                            <thead class="bg-success">
                                <tr>
                                    <th>क्र.</th>
                                    <th>वित्तीय वर्ष</th>
                                    <th>कुल कार्य</th>
                                    @foreach (get_work_statuses() as $status)
                                        <th>{{ $status->work_status_name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($financial_years_data as $fy)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $fy->name }}</td>
                                        <td><a href="{{ route('work.index') }}">{{ $fy->total_works }}</a></td>
                                        @foreach (get_work_statuses() as $status)
                                            <td class="count">{{ $fy->fy_work_stage[$status->work_status_id] }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info lobidisable">
                    <div class="panel-heading">
                        <h4 class="panel-title">ग्रामीण आंकड़े</h4>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-striped table-bordered table-condensed m-0">
                            <thead class="bg-info">
                                <tr>
                                    <th>क्र.</th>
                                    <th>विकासखण्ड</th>
                                    <th>कुल कार्य</th>
                                    @foreach (get_work_statuses() as $status)
                                        <th>{{ $status->work_status_name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($block_data as $block)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td class="text-left"><a
                                                href="{{ route('reports.grampanchayat-wise') . '?block=' . $block->block_id }}">
                                                {{ $block->block_name }} </a></td>
                                        <td class="count"> {!! ($data = $block->total_works)
                                            ? '<a href="' . route('reports.works') . '?block=' . $block->block_id . '">' . $data . '</a>'
                                            : '-' !!} </td>
                                        @foreach (get_work_statuses() as $status)
                                            <td class="count">{!! ($data = $block->work_stage_data[$status->work_status_id])
                                                ? '<a href="' .
                                                    route('reports.works') .
                                                    '?block=' .
                                                    $block->block_id .
                                                    '&status=' .
                                                    $status->work_status_id .
                                                    '">' .
                                                    $data .
                                                    '</a>'
                                                : '-' !!}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4> नगरीय निकाय के आंकड़े </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-condensed text-center m-0">
                            <thead class="bg-info">
                                <tr>
                                    <th width="5%">क्र.</th>
                                    <th class="text-left">नगर का नाम</th>
                                    <th>कुल कार्य</th>
                                    @foreach (get_work_statuses() as $status)
                                        <th>{{ $status->work_status_name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl=1; @endphp
                                @foreach ($city_data as $city)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td class="text-left"><a
                                                href="{{ route('reports.ward-wise') . '?city=' . $city->city_id }}">
                                                {{ $city->city_name }} </a></td>
                                        <td class="count"> {!! ($data = $city->total_works)
                                            ? '<a href="' . route('reports.works') . '?city=' . $city->city_id . '">' . $data . '</a>'
                                            : '-' !!} </td>
                                        @foreach (get_work_statuses() as $status)
                                            <td class="count">{!! ($data = $city->work_stage_data[$status->work_status_id])
                                                ? '<a href="' .
                                                    route('reports.works') .
                                                    '?city=' .
                                                    $city->city_id .
                                                    '&status=' .
                                                    $status->work_status_id .
                                                    '">' .
                                                    $data .
                                                    '</a>'
                                                : '-' !!}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12">
                <div class="panel panel-warning lobidisable">
                    <div class="panel-heading">
                        <h4 class="panel-title">एजेंसी वार</h4>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-striped table-bordered table-condensed m-0">
                            <thead class="bg-warning">
                                <tr>
                                    <th>क्र.</th>
                                    <th>एजेंसी</th>
                                    <th>कुल कार्य</th>
                                    @foreach (get_work_statuses() as $status)
                                        <th>{{ $status->work_status_name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl=1; @endphp
                                @foreach ($office_data as $office)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td class="text-left">{{ $office->office_name }}</td>
                                        <td class="count"> {!! ($data = $office->total_works)
                                            ? '<a href="' . route('reports.works') . '?agency=' . $office->office_id . '">' . $data . '</a>'
                                            : '-' !!} </td>
                                        @foreach (get_work_statuses() as $status)
                                            <td class="count">{!! ($data = $office->work_stage_data[$status->work_status_id])
                                                ? '<a href="' .
                                                    route('reports.works') .
                                                    '?agency=' .
                                                    $office->office_id .
                                                    '&status=' .
                                                    $status->work_status_id .
                                                    '">' .
                                                    $data .
                                                    '</a>'
                                                : '-' !!}</td>
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
