@extends('layout.app')
@section('content')
    @include('layout.includes.page_header',['icon'=>'files','title'=>'कार्य श्रेणीवार रिपोर्ट'])
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title">कार्य श्रेणीवार रिपोर्ट</h4>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-striped table-bordered table-condensed text-center m-0" id="datatable">
                            <thead class="bg-success">
                            <tr>
                                <th>क्र.</th>
                                <th>कार्य श्रेणी</th>
                                <th>कार्य प्रकार</th>
                                <th>कुल कार्य</th>
                                <th>स्वीकृत राशि</th>
                                @foreach(get_work_statuses() as $status)
                                    <th>{{ $status->work_status_name }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @php $i = 1; @endphp
                            @forelse($category_data as $category)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td class="text-left">{{ $category->work_category_name }}</td>
                                    <td>{{ $category->work_types_count }}</td>
                                    <td><b>{{ $category->total_works }}</b></td>
                                    <td class="text-right">{{ number_format($category->sanctioned, 2) }}</td>
                                    @foreach(get_work_statuses() as $status)
                                        <td>{{ $category->work_stage_data[$status->work_status_id] ?: '-' }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr><td colspan="{{ 5 + count(get_work_statuses()) }}" class="text-center">
                                    कोई कार्य श्रेणी दर्ज नहीं है। मास्टर डाटा → कार्य श्रेणी से जोड़ें।
                                </td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
