@extends('layout.app')
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','text'=>'ग्राम'])
    <!-- Main content -->
    <div class="content">

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>
                                ग्राम सूची
                                <span class="pull-right">
                                    <button class="btn btn-success" onclick="openAddModal('नया ग्राम जोड़ें','{{ route('village.create') }}')">नया ग्राम जोड़ें</button>
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0" id="datatable">
                            <thead class="bg-info">
                            <tr>
                                <th width="5%">क्र.</th>
                                <th>ग्राम का नाम</th>
                                <th>ग्राम (अंग्रेज़ी)</th>
                                <th>ग्राम पंचायत</th>
                                <th>विकासखंड</th>
                                <th width="20%">कार्यवाही</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sl = 1; @endphp
                            @foreach($villages as $list)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $list->village_name }}</td>
                                    <td>{{ $list->village_name_en }}</td>
                                    <td>{{ $list->grampanchayat->grampanchayat_name ?? '—' }}</td>
                                    <td>{{ $list->grampanchayat->block->block_name ?? '—' }}</td>
                                    <td>
                                        <button onclick="openEditModal('ग्राम संपादित करें','{{ route('village.edit', $list->village_id) }}');" class="btn btn-primary btn-xs">
                                            <i class="ti-pencil"></i> संपादित करें
                                        </button>
                                        <button onclick="openDeleteModal('क्या आप निश्चित हैं ?','{{ route('village.show', $list->village_id) }}');" class="btn btn-danger btn-xs">
                                            <i class="ti-trash"></i> हटाएं
                                        </button>
                                    </td>
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
