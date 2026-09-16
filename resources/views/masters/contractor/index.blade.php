@extends('layout.app')
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','title'=>'ठेकेदार सूची'])
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>
                                ठेकेदार सूची
                                <span class="pull-right">
                                    <button class="btn btn-success" onclick="openAddModal('नया ठेकेदार जोड़ें','{{ route('contractor.create') }}')">नया ठेकेदार जोड़ें</button>
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0" id="datatable">
                            <thead class="bg-info">
                            <tr>
                                <th width="5%">क्र.</th>
                                <th>ठेकेदार का नाम</th>
                                <th>संपर्क व्यक्ति</th>
                                <th>मोबाइल</th>
                                <th>पंजीयन क्रमांक</th>
                                <th class="text-center">कार्य आदेश</th>
                                <th width="20%">कार्यवाही</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sl = 1; @endphp
                            @forelse($contractors as $list)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $list->contractor_name }}</td>
                                    <td>{{ $list->contact_person ?? '—' }}</td>
                                    <td>{{ $list->mobile ?? '—' }}</td>
                                    <td>{{ $list->registration_no ?? '—' }}</td>
                                    <td class="text-center">{{ $list->agreements_count }}</td>
                                    <td>
                                        <button onclick="openEditModal('ठेकेदार संपादित करें','{{ route('contractor.edit', $list->contractor_id) }}');" class="btn btn-primary btn-xs">
                                            <i class="ti-pencil"></i> संपादित करें
                                        </button>
                                        <button onclick="openDeleteModal('क्या आप निश्चित हैं ?','{{ route('contractor.show', $list->contractor_id) }}');" class="btn btn-danger btn-xs">
                                            <i class="ti-trash"></i> हटाएं
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">कोई ठेकेदार दर्ज नहीं है</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
