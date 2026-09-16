@extends('layout.app')
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','title'=>'कार्य श्रेणी'])
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>
                                कार्य श्रेणी सूची
                                <span class="pull-right">
                                    <button class="btn btn-success" onclick="openAddModal('नई कार्य श्रेणी','{{ route('work_category.create') }}')">नई श्रेणी जोड़ें</button>
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0" id="datatable">
                            <thead class="bg-info">
                            <tr>
                                <th width="5%">क्र.</th>
                                <th>श्रेणी का नाम</th>
                                <th class="text-center">कार्य प्रकार</th>
                                <th width="22%">कार्यवाही</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sl = 1; @endphp
                            @forelse($categories as $list)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $list->work_category_name }}</td>
                                    <td class="text-center">{{ $list->work_types_count }}</td>
                                    <td>
                                        <button onclick="openEditModal('श्रेणी संपादित करें','{{ route('work_category.edit', $list->work_category_id) }}');" class="btn btn-primary btn-xs">
                                            <i class="ti-pencil"></i> संपादित करें
                                        </button>
                                        <button onclick="openDeleteModal('क्या आप निश्चित हैं ?','{{ route('work_category.show', $list->work_category_id) }}');" class="btn btn-danger btn-xs">
                                            <i class="ti-trash"></i> हटाएं
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">कोई कार्य श्रेणी दर्ज नहीं है</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
