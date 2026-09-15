@extends('layout.app')
{{-- @section('title', 'Dashboard') --}}
@section('content')
    @include('layout.includes.page_header', ['icon' => 'home', 'text' => 'Dashboard'])
    <!-- Main content -->
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>
                                सूची
                                <span class="pull-right">
                                    <a href="{{ route('work.create') }}" class="btn btn-success">नया कार्य जोड़ें</a>
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-sm m-0">
                            <thead class="bg-info">
                                <tr>
                                    <th>क्र.</th>
                                    <th>कार्य आईडी</th>
                                    <th>कार्य नाम</th>
                                    <th>एजेंसी</th>
                                    <th>योजना</th>
                                    <th>यूनिट</th>
                                    <th>क्षेत्र</th>
                                    <th>गांव/वार्ड</th>
                                    <th>कारवाही</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @foreach ($work as $list)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>{{ $list->work_id }}</td>
                                        <td>{{ $list->work_name }}</td>
                                        <td>{{ $list->office->office_name }}</td>
                                        <td>{{ $list->scheme->scheme_name }}</td>
                                        <td>{{ $list->units_of_work }}</td>
                                        <td>{{ ($list->location_type->location_type_id == 1) ? 'ग्रामीण':'नगरी' }}</td>
                                        <td>
                                      @if ($list->location_type->location_type_id == 1)
                                          {{ $list->village->village_name_en }}
                                      @else
                                          {{ $list->ward->ward_name }}
                                      @endif
                                        <td>
                                            <a href="{{ route('work.edit', $list->work_id) }}"
                                                class="btn btn-primary btn-xs" id=""><i class="ti-pencil"></i>
                                                Edit</a>

                                            <button onclick="openDeleteModal('Are You Sure You Want to Permanently Delete Record ?','{{route('work.show',$list->work_id)}}');" class="btn btn-danger btn-xs">
                                                <i class="ti-trash"></i> Delete
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
