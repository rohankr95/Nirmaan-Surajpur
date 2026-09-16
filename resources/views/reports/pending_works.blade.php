@extends('layout.app')
@section('content')
    @include('layout.includes.page_header',['icon'=>'exclamation','title'=>$title])
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>{{ $title }} <span class="badge">{{ $work_data->count() }}</span></h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0" id="datatable">
                            <thead class="bg-warning">
                            <tr>
                                <th width="5%">क्र.</th>
                                <th>कार्य का नाम</th>
                                <th>एजेंसी</th>
                                <th>योजना</th>
                                <th>कार्य स्थिति</th>
                                <th width="14%">कार्यवाही</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sl = 1; @endphp
                            @forelse($work_data as $work)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $work->work_name }}</td>
                                    <td>{{ $work->office->office_name ?? '—' }}</td>
                                    <td>{{ $work->scheme->scheme_name ?? '—' }}</td>
                                    <td>{{ $work->status->work_status_name ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('reports.work-details', $work->work_id) }}" class="btn btn-primary btn-xs">
                                            <i class="ti-eye"></i> विवरण देखें
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">कोई कार्य लंबित नहीं है</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
