@extends('layout.app')
@section('title','कार्य विवरण | निर्माण')
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','title'=>'कार्य विवरण'])
    <!-- Main content -->
    <div class="content">

        <div class="row">

            <div class="col-lg-9">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>कार्य विवरण</h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-condensed m-0">
                            <tbody>
                                <tr>
                                    <td>कार्य का नाम</td>
                                    <th class="bg-success">{{ $work->work_name }}</th>
                                </tr>
                                <tr>
                                    <td>आवंटित कर्मचारी</td>

                                    <th class="bg-success">{{ $work->employee->emp_name??'' }}</th>
                                </tr>
                                <tr>
                                    <td>क्षेत्र</td>
                                    <td class="p-0">
                                        <table class="table table-condensed table-bordered m-0">
                                            <tbody>
                                            @if($work->ward_id)
                                                <tr><td>नगर</td><td class="bg-success"><b>{{ $work->ward?->city?->city_name ?? '—' }}</b></td></tr>
                                                <tr><td>वार्ड</td><td class="bg-success"><b>{{ $work->ward?->ward_name ?? '—' }}</b></td></tr>
                                            @else
                                                <tr><td>विकासखण्ड</td><td class="bg-success"><b>{{ $work->village?->grampanchayat?->block?->block_name ?? '—' }}</b></td></tr>
                                                <tr><td>ग्रामपंचायत</td><td class="bg-success"><b>{{ $work->village?->grampanchayat?->grampanchayat_name ?? '—' }}</b></td></tr>
                                                <tr><td>ग्राम</td><td class="bg-success"><b>{{ $work->village?->village_name ?? '—' }}</b></td></tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>कार्य प्रकार</td>
                                    <th class="bg-success">{{ $work->work_type?->work_type_name ?? '—' }}</th>
                                </tr>
                                <tr>
                                    <td>कार्य यूनिट </td>
                                    <th class="bg-success">{{ $work->units_of_work }}</th>
                                </tr>
                                <tr>
                                    <td>योजना का नाम</td>
                                    <th class="bg-success">{{ $work->scheme?->scheme_name ?? '—' }}</th>
                                </tr>
                                <tr>
                                    <td>विभाग</td>
                                    <th class="bg-success">{{ $work->department?->department_name ?? '—' }}</th>
                                </tr>
                                <tr>
                                    <td>वित्तीय वर्ष</td>
                                    <th class="bg-success">{{ $work->financial_year?->name ?? '—' }}</th>
                                </tr>
                                <tr>
                                    <td>एजेंसी</td>
                                    <th class="bg-success">{{ $work->office?->office_name ?? '—' }}</th>
                                </tr>
{{--                                    <th>क्षेत्र</th>--}}
{{--                                    <th>एजेंसी</th>--}}
{{--                                    <th>तकनीकी स्वीकृति</th>--}}
{{--                                    <th>प्रशासकीय  स्वीकृति</th>--}}
{{--                                    <th>निविदा स्वीकृति</th>--}}
{{--                                    <th>कार्य प्रगति चरण</th>--}}
{{--                                    <th>कार्य विवरण</th>--}}

                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                            <h4>उत्तरदायी अधिकारी</h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-condensed table-bordered m-0">
                            <tbody>
                                <tr>
                                    <td colspan="2"><b>उप अभियंता</b></td>
                                </tr>
                                <tr>
                                    <td>नाम</td>
                                    <td>{{ $work->employee->emp_name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>मोबाइल न.</td>
                                    <td>{{ $work->employee->emp_mobile ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><b>एसडीओ</b></td>
                                </tr>
                                <tr>
                                    <td>नाम</td>
                                    <td>{{ $work->sdo->emp_name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>मोबाइल न.</td>
                                    <td>{{ $work->sdo->emp_mobile ?? '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                            <h4>वर्तमान स्थिति</h4>
                        </div>
                    </div>
                    <div class="panel-body">
                            <h3 class="text-center {{ ($work->work_status==9)?'text-success':'' }}{{ ($work->work_status==10)?'text-danger':'' }}">{{ $work->status?->work_status_name ?? '—' }}</h3>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-condensed text-center table-bordered m-0">
                            <tbody class="">
                                <tr class=""><td>प्रविष्टि दिनांक</td><td><b>{{ ($work->created_at)?date('d-m-Y',strtotime($work->created_at)):''  }}</b></td></tr>
                                <tr class=""><td>अंतिम संशोधन</td><td><b>{{ ($work->updated_at)?date('d-m-Y',strtotime($work->updated_at)):''  }}</b></td></tr>
                            </tbody>
                        </table>

                        <div style="margin:20px;" class="text-center">
                            <a href="{{ route('work.edit', $work->work_id) }}" class="btn btn-labeled btn-primary">
                                <span class="btn-label"><i class="fa fa-pencil"></i></span>संपादित करें
                            </a>
                        </div>

                        <div style="margin:20px;" class="text-center">
                            <a href="{{ route('reports.work-dossier', $work->work_id) }}" target="_blank" class="btn btn-labeled btn-success">
                                <span class="btn-label"><i class="fa fa-print"></i></span>कार्य प्रपत्र प्रिंट करें
                            </a>
                        </div>

                        <div style="margin:20px;" class="text-center">
                            <button onclick="openDeleteModal('क्या आप रिकॉर्ड को स्थायी रूप से हटाना चाहते हैं ?','{{route('work.show',$work->work_id)}}');" class="btn btn-labeled btn-danger">
                                <span class="btn-label"><i class="fa fa-trash"></i></span>कार्य हटाये
                            </button>
                        </div>

                    </div>
                </div>
            </div>



            @if($work->ts_id)
            <div class="col-lg-4">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                            <h4>तकनीकी स्वीकृति
                                <span class="pull-right">
                                    <button onclick="openEditModal('तकनीकी स्वीकृति संपादन','{{route('technical-sanction.edit',$work->work_id)}}');" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body bg-success" style="padding: 2px">
                        @include('reports.works.ts_view',['technicalSanction'=>$work->technical_sanction,'detail_page'=>true])
                        </div>
                </div>
            </div>
            @endif
            @if($work->as_id)
            <div class="col-lg-4">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                            <h4>प्रशासकीय  स्वीकृति
                                <span class="pull-right">
                                    <button onclick="openEditModal('प्रशासकीय  स्वीकृति संपादन','{{route('administrative-sanction.edit',$work->work_id)}}');" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body bg-success" style="padding: 2px">
                        @include('reports.works.as_view',['administrativeSanction'=>$work->administrative_sanction,'detail_page'=>true])
                        </div>
                </div>
            </div>
            @endif
            @if($work->tender_id)

            <div class="col-lg-4">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                            <h4>निविदा स्वीकृति
                                <span class="pull-right">
                                    <button onclick="openEditModal('निविदा स्वीकृति संपादन','{{route('tender.edit',$work->work_id)}}');" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body bg-success" style="padding: 2px">
                        @include('reports.works.tender_view',['tender'=>$work->tender,'detail_page'=>true])
                        </div>
                </div>
            </div>
            @endif






            @if($work->work_stage)
            <div class="col-lg-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                            <h4>कार्य प्रगति विवरण</h4>
                        </div>
                    </div>
                    <div class="panel-body p-0 bg-success" style="padding: 2px">
                        @include('Work-Progress.work_progress_list',['work'=>$work])
                    </div>
                </div>
            </div>
            @endif

            <div class="col-lg-12">
                @include('reports.works._documents',['work'=>$work])
            </div>

            <div class="col-lg-12">
                @include('reports.works._photo_gallery',['work'=>$work])
            </div>

            <div class="col-lg-12">
                @include('reports.works._location_map',['work'=>$work])
            </div>

            <div class="col-lg-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                            <h4>पूर्ववृत्त जानकारी</h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th width="5%">क्र.</th>
                                    <th width="18%">दिनांक</th>
                                    <th width="22%">उपयोगकर्ता</th>
                                    <th>विवरण</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($activity ?? []) as $index => $entry)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $entry->created_at ? $entry->created_at->format('d-m-Y H:i') : '-' }}</td>
                                        <td>{{ $entry->User->name ?? '-' }}</td>
                                        <td>{{ $entry->label }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">इस कार्य के लिए कोई प्रविष्टि नहीं मिली</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
