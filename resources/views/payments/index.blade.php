@extends('layout.app')
@section('content')
    @include('layout.includes.page_header',['icon'=>'wallet','title'=>'कार्य भुगतान सूची'])
    <div class="content">

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form method="get" action="{{ route('payments.index') }}" class="form-inline">
                            <label for="financial_year">वित्तीय वर्ष</label>
                            <select name="financial_year" id="financial_year" class="form-control">
                                <option value="">-- वित्तीय वर्ष चुनें --</option>
                                @foreach($financial_years as $fy)
                                    <option value="{{ $fy->id }}" {{ echo_selected($request->financial_year == $fy->id) }}>{{ $fy->name }}</option>
                                @endforeach
                            </select>
                            <label for="scheme">योजना</label>
                            <select name="scheme" id="scheme" class="form-control">
                                <option value="">-- योजना चुनें --</option>
                                @foreach(get_schemes() as $scheme)
                                    <option value="{{ $scheme->scheme_id }}" {{ echo_selected($request->scheme == $scheme->scheme_id) }}>{{ $scheme->scheme_name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> खोजें</button>
                            <a href="{{ route('payments.index') }}" class="btn btn-default">रीसेट</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @php
            $totSanction = $work_data->sum('sanction_amount');
            $totReleased = $work_data->sum(fn($w) => $w->released_amount);
            $totSpent    = $work_data->sum(fn($w) => $w->expenditure_amount);
        @endphp

        <div class="row">
            <div class="col-sm-4">
                <div class="panel panel-primary"><div class="panel-body text-center">
                    <h4 class="m-0">₹ {{ number_format($totSanction, 2) }}</h4>
                    <small>कुल स्वीकृत राशि</small>
                </div></div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-success"><div class="panel-body text-center">
                    <h4 class="m-0">₹ {{ number_format($totReleased, 2) }}</h4>
                    <small>कुल जारी राशि</small>
                </div></div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-warning"><div class="panel-body text-center">
                    <h4 class="m-0">₹ {{ number_format($totSpent, 2) }}</h4>
                    <small>कुल व्यय राशि</small>
                </div></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title"><h4>भुगतान की सूची</h4></div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0" id="datatable">
                            <thead class="bg-info">
                            <tr>
                                <th width="4%">क्र.</th>
                                <th>कार्य का नाम</th>
                                <th>ग्राम / वार्ड</th>
                                <th>योजना</th>
                                <th>वित्तीय वर्ष</th>
                                <th class="text-right">स्वीकृत राशि</th>
                                <th class="text-right">जारी राशि</th>
                                <th class="text-right">व्यय राशि</th>
                                <th class="text-right">मूल्यांकन राशि</th>
                                <th class="text-right">शेष राशि</th>
                                <th width="16%">कार्यवाही</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sl = 1; @endphp
                            @forelse($work_data as $work)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $work->work_name }}</td>
                                    <td>{{ $work->village->village_name ?? $work->ward->ward_name ?? '—' }}</td>
                                    <td>{{ $work->scheme->scheme_name ?? '—' }}</td>
                                    <td>{{ $work->financial_year->name ?? '—' }}</td>
                                    <td class="text-right">{{ number_format((float) $work->sanction_amount, 2) }}</td>
                                    <td class="text-right">{{ number_format($work->released_amount, 2) }}</td>
                                    <td class="text-right">{{ number_format($work->expenditure_amount, 2) }}</td>
                                    <td class="text-right">{{ number_format($work->evaluation_amount, 2) }}</td>
                                    <td class="text-right {{ $work->balance_amount < 0 ? 'text-danger' : '' }}">
                                        <b>{{ number_format($work->balance_amount, 2) }}</b>
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-xs"
                                                onclick="openAddModal('भुगतान दर्ज करें','{{ route('payments.create', ['work_id' => $work->work_id]) }}')">
                                            <i class="ti-plus"></i> भुगतान जोड़ें
                                        </button>
                                        <button class="btn btn-primary btn-xs"
                                                onclick="openEditModal('भुगतान इतिहास','{{ route('payments.history', ['work_id' => $work->work_id]) }}')">
                                            <i class="ti-time"></i> इतिहास
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="11" class="text-center">कोई कार्य नहीं मिला</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
