@extends('layout.app')
@section('title','कार्य रिपोर्ट | निर्माण')
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','text'=>'Dashboard'])
    <!-- Main content -->
    <div class="content">

        <div class="row" hidden>
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>कार्य रिपोर्ट</h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form>
                            <div class="row m-b-10">
                                <div class="col-lg-6">
                                        <label>कार्य का नाम</label>
                                        <input type="search" class="form-control" placeholder="कार्य का नाम" name="name" value="{{ $request->name }}">
                                </div>
                                <div class="col-lg-3">
                                        <label>एजेंसी</label>
                                        <select class="form-control form-select" name="agency">
                                            <option value="">-- सभी --</option>
                                            @foreach(get_offices() as $office)
                                                <option value="{{ $office->office_id }}" {{ echo_selected($request->agency==$office->office_id) }}>{{ $office->office_name }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-lg-3">
                                    <label>योजना</label>
                                    <select class="form-control form-select" name="scheme">
                                        <option value="">-- सभी --</option>
                                        @foreach(get_schemes() as $scheme)
                                            <option value="{{ $scheme->scheme_id }}" {{ echo_selected($request->scheme==$scheme->scheme_id) }}>{{ $scheme->scheme_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row m-b-10">
                                <div class="col-lg-3">
                                    <label>विभाग</label>
                                    <select class="form-control form-select" name="department">
                                        <option value="">-- सभी --</option>
                                        @foreach(get_departments() as $department)
                                            <option value="{{ $department->department_id }}" {{ echo_selected($request->department==$department->department_id) }}>{{ $department->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                        <label>कार्य प्रवेश दिनांक से</label>
                                        <input type="date" class="form-control" name="to" value="{{$request->to}}">
                                </div>
                                <div class="col-lg-3">
                                        <label>कार्य प्रविष्टि दिनांक तक</label>
                                        <input type="date" class="form-control" name="from" value="{{ $request->from }}">
                                </div>
                                <div class="col-lg-3">
                                    <label>वित्तीय वर्ष </label>
                                    <select class="form-control form-select" name="financial_year">
                                        <option value="">-- सभी --</option>
                                        @foreach(get_financial_years() as $fy)
                                            <option value="{{ $fy->id }}" {{ echo_selected($request->financial_year==$fy->id) }}>{{ $fy->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row m-t-20">
                                <div class="col-lg-12 text-center">
                                    <div class="form-group">
                                        <input class="btn btn-success btn-lg" name="submit" type="submit" value="फ़िल्टर करें">
                                        <input class="btn btn-success btn-lg" name="submit" type="submit" value="फ़िल्टर करें">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                            <h4>
                                कार्य सूची {{ isset($title)?'- '.$title:'' }}

                                {{ ($request->block)?' - '.\App\Models\Block::find($request->block)->block_name:'' }}
                                {{ ($request->grampanchayat)?' - '.\App\Models\Village::find($request->grampanchayat)->grampanchayat_name:'' }}
                                {{ ($request->village)?' - '.\App\Models\Grampanchayat::find($request->village)->village_name:'' }}
                                {{ ($request->city)?' - '.\App\Models\City::find($request->city)->city_name:'' }}
                                {{ ($request->ward)?' - '.\App\Models\Ward::find($request->ward)->ward_name:'' }}

                                {{ ($request->agency)?' - '.\App\Models\Office::find($request->agency)->office_name:'' }}
                                {{ ($request->scheme)?' - '.\App\Models\Scheme::find($request->scheme)->scheme_name:'' }}

                            @if(isset($addWork))
                                <span class="pull-right">
                                    <a href="{{ route('work.create') }}" class="btn btn-labeled btn-success m-b-5">
                                        <span class="btn-label"><i class="fa fa-plus"></i></span>नया कार्य जोड़ें
                                    </a>
                                </span>
                            @endif
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-condensed m-0" id="dataTableExample2">
                            <thead class="bg-info">
                                <tr>
                                    <th width="5%">क्र.</th>
                                    <th>कार्य का नाम</th>
                                    <th>क्षेत्र</th>
                                    <th>एजेंसी</th>
{{--                                    <th>योजना</th>--}}
                                    <th>तकनीकी स्वीकृति</th>
                                    <th>प्रशासकीय  स्वीकृति</th>
                                    <th>निविदा स्वीकृति</th>
                                    <th>कार्य प्रगति चरण</th>
                                    <th>कार्य विवरण</th>
{{--                                    @if(isset($ts) || isset($as)|| isset($tender)|| isset($wp))--}}
                                    <th>कार्यवाही</th>
{{--                                    @endif--}}
                                </tr>
                            </thead>
                            <tbody>
                            @php $sl=1; @endphp
                            @foreach($work_data as $work)

                            @php    
                                $tableColor =  '';
                                if($work->work_status==10)
                                    $tableColor =  'bg-success';
                                if($work->work_status==12)
                                    $tableColor =  'bg-danger';

                        // echo ($work->work_complete->upload_file??'');
                            @endphp
                                <tr class="{{ $tableColor }}">
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $work->work_name }}</td>
                                    <td class="p-0">
                                        <table class="table table-condensed table-bordered m-0 ">
                                            <tbody class="{{ $tableColor }}">
                                                @if($work->ward_id)
                                                <tr><td>नगर</td><td><b>{{ $work->ward->city->city_name }}</b></td></tr>
                                                <tr><td>वार्ड</td><td><b>{{ $work->ward->ward_name }}</b></td></tr>
                                                @else
                                                <tr><td>विकासखण्ड</td><td><b>{{ $work->village->grampanchayat->block->block_name }}</b></td></tr>
                                                <tr><td>ग्रामपंचायत</td><td><b>{{ $work->village->grampanchayat->grampanchayat_name }}</b></td></tr>
                                                <tr><td>ग्राम</td><td><b>{{ $work->village->village_name }}</b></td></tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>{{ $work->office->office_name }}</td>
{{--                                    <td>{{ $work->scheme->scheme_name }}</td>--}}
                                    <td class="p-0">
                                        <table class="table table-condensed text-center table-bordered m-0">
                                            <tbody class="{{ $tableColor }}">
                                            @if($work->ts_id)
                                                <tr><td><b>TS NO - {{ $work->technical_sanction->ts_no??'' }}</b></td></tr>
                                                <tr><td>{!! ($work->technical_sanction->approval_date??'')?'<span class="text-success"><i class="fa fa-check m-r-2"></i>Approved</span>':'<span class="text-muted">Not Approved</span>' !!}</td></tr>
                                                <tr><td><button onclick="openEditModal('तकनीकी स्वीकृति - {{ $work->technical_sanction->ts_no??'' }}','{{ route('reports.ts-view',$work->ts_id) }}')" class="btn btn-xs btn-primary btn-block">देखें</button></td></tr>
                                            @else
                                                <tr><td>-</td></tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="p-0">
                                        <table class="table table-condensed text-center table-bordered m-0">
                                            <tbody class="{{ $tableColor }}">
                                            @if($work->as_id)
                                                <tr><td><b>AS NO - {{ $work->administrative_sanction->as_no??'' }}</b></td></tr>
                                                <tr><td>{!! ($work->administrative_sanction->approval_date??'')?'<span class="text-success"><i class="fa fa-check m-r-2"></i>Approved</span>':'<span class="text-muted">Not Approved</span>' !!}</td></tr>
                                                <tr><td><button onclick="openEditModal('प्रशासकीय  स्वीकृति - {{ $work->administrative_sanction->as_no??'' }}','{{ route('reports.as-view',$work->as_id) }}')" class="btn btn-xs btn-primary btn-block">देखें</button></td></tr>
                                            @else
                                                <tr><td>-</td></tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="p-0">
                                        <table class="table table-condensed text-center table-bordered m-0">
                                            <tbody class="{{ $tableColor }}">
                                            @if($work->tender_id)
                                                <tr><td><b>TENDER - {{ $work->tender->tender_no??'' }}</b></td></tr>
                                                <tr><td>{!! ($work->tender->work_order_date??'')?'<span class="text-success"><i class="fa fa-check m-r-2"></i>Work Order</span>':'<span class="text-muted">Pending</span>' !!}</td></tr>
                                                <tr><td><button onclick="openEditModal('निविदा स्वीकृति - {{ $work->tender->tender_no??'' }}','{{ route('reports.tender-view',$work->tender_id) }}')" class="btn btn-xs btn-primary btn-block">देखें</button></td></tr>
                                            @else
                                                <tr><td>-</td></tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="p-0">
                                        <table class="table table-condensed text-center table-bordered m-0">
                                            <tbody class="{{ $tableColor }}">
                                            @if($work->work_stage)
                                                <tr><td><b>{{ $work->stage->work_type_stage_name }}</b></td></tr>
                                                <tr><td>{{ ($work->stage->stage_number) }}/{{ ($work->work_type->work_stages->count()) }}</td></tr>
                                                <tr><td><button onclick="openEditModal('कार्य प्रगति चरण','{{ route('reports.progress-view',$work->work_id) }}')" class="btn btn-xs btn-primary btn-block">देखें</button></td></tr>
                                            @else
                                                <tr><td>-</td></tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="p-0">
                                        <table class="table table-condensed text-center table-bordered m-0">
                                            <tbody class="{{ $tableColor }}">
                                            <tr><td colspan="2"><b class="{{ ($work->work_status==10)?'text-success':'' }}{{ ($work->work_status==12)?'text-danger':'' }}">{{ $work->status->work_status_name }}</b></td></tr>
                                            <tr class="text-muted small"><td>प्रविष्टि दिनांक</td><td><b>{{ ($work->created_at)?date('d-m-Y',strtotime($work->created_at)):''  }}</b></td></tr>
                                            <tr class="text-muted small"><td>अंतिम संशोधन</td><td><b>{{ ($work->updated_at)?date('d-m-Y',strtotime($work->updated_at)):''  }}</b></td></tr>
                                            <tr>
                                               @if ($work->work_status==10)
                                               <td colspan="2">{!! ($work->work_complete->upload_file??'')?'<a href="'. asset($work->work_complete->upload_file??'') .'" target="_blank" class="btn btn-xs btn-primary btn-block">देखें</a>':('दस्तावेज़ नहीं मिली') !!}
                                               </td>
                                               @endif
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    @if(isset($ts)) 
                                    @if($work->ts_id)
                                    <td><a href="{{ route('work-progress.create') }}?work_id={{ $work->work_id }}&work_status={{ 3}}" class="btn btn-success">तकनीकी स्वीकृति अपडेट करें</a><br><br>
                                        <button onclick="openEditModal('तकनीकी स्वीकृति संपादन','{{route('technical-sanction.edit',$work->work_id)}}');" class="btn btn-labeled btn-purple">
                                            <span class="btn-label"><i class="fa fa-pencil"></i></span>संपादित करें
                                        </button>
                                    </td>
                                @else
                                    <td><a href="{{ route('work-progress.create') }}?work_id={{ $work->work_id }}&work_status={{ 2 }}" class="btn btn-primary">तकनीकी स्वीकृति जोड़ें</a><br><br>
                                        <button onclick="openEditModal('तकनीकी स्वीकृति संपादन','{{route('technical-sanction.edit',$work->work_id)}}');" class="btn btn-labeled btn-purple">
                                            <span class="btn-label"><i class="fa fa-pencil"></i></span>संपादित करें
                                        </button>
                                        </a>
                                    </td>
                                @endif
                                    @elseif (isset($as))
                                        @if($work->as_id)
                                            <td>
                                               
                                                <a href="{{ route('work-progress.create') }}?work_id={{ $work->work_id }}&work_status={{ 5 }}" class="btn btn-success">प्रशासकीय स्वीकृति अपडेट करें</a>
                                              <br><br>
                                             
                                                    <button onclick="openEditModal('प्रशासकीय  स्वीकृति संपादन','{{route('administrative-sanction.edit',$work->work_id)}}');" class="btn btn-labeled btn-purple">
                                                        <span class="btn-label"><i class="fa fa-pencil"></i></span>संपादित करें
                                                    </button>
                                                  
                                            </td>
                                        @else
                                            <td><a href="{{ route('work-progress.create') }}?work_id={{ $work->work_id }}&work_status={{ 4 }}" class="btn btn-primary">प्रशासकीय  स्वीकृति जोड़ें</a>
                                                <br><br>
                                                    <button onclick="openEditModal('प्रशासकीय  स्वीकृति संपादन','{{route('administrative-sanction.edit',$work->work_id)}}');" class="btn btn-labeled btn-purple">
                                                        <span class="btn-label"><i class="fa fa-pencil"></i></span>संपादित करें
                                                    </button>
                                            </td>
                                        @endif
                                    @elseif(isset($tender))
                                        @if($work->tender_id)
                                            <td><a href="{{ route('work-progress.create') }}?work_id={{ $work->work_id }}&work_status={{ 7 }}" class="btn btn-success">निविदा स्वीकृति अपडेट करें</a>
                                                <br><br>
                                                <button onclick="openEditModal('निविदा स्वीकृति संपादन','{{route('tender.edit',$work->work_id)}}');" class="btn btn-labeled btn-purple">
                                                    <span class="btn-label"><i class="fa fa-pencil"></i></span>संपादित करें
                                                </button>
                                            </td>
                                        @else
                                            <td><a href="{{ route('work-progress.create') }}?work_id={{ $work->work_id }}&work_status={{ 6 }}" class="btn btn-primary">निविदा जोड़ें</a>
                                                <br><br>
                                                    <button onclick="openEditModal('निविदा स्वीकृति संपादन','{{route('tender.edit',$work->work_id)}}');" class="btn btn-labeled btn-purple">
                                                        <span class="btn-label"><i class="fa fa-pencil"></i></span>संपादित करें
                                                    </button>
                                            </td>
                                        @endif
                                    @elseif(isset($wp))
                                            <td><a href="{{ route('work-progress.create') }}?work_id={{ $work->work_id }}&work_status={{ 9 }}" class="btn btn-success">प्रोग्रेस अपडेट करें</a></td>
                                    @else
                                        <td class="p-0">
                                            <table class="table table-bordered m-0">
                                                <tbody class="{{ $tableColor }}">
                                                <tr>
                                                    <td class="p-0">
                                                        <a href="{{ route('reports.work-details',$work->work_id) }}" class="btn btn-labeled btn-primary btn-block m-b-10"><span class="btn-label"><i class="fa fa-eye"></i></span>कार्य देखें</a>
{{--                                                        <a href="{{ route('work-progress.create') }}?work_id={{ $work->work_id }}&work_status={{ $work->work_status }}" class="btn btn-primary btn-xs btn-block"><i class="fa fa-eye"></i></a>--}}
                                                    </td>
                                                </tr>
                                                @if(($work->work_status!=9)&&($work->work_status!=10))
                                                <tr>
                                                    <td class="p-0">
                                                        <table class="table table-bordered table-condensed m-0">
                                                            <tr>
                                                                {{-- <td class="p-0"><a href="{{ route('work-progress.create') }}?work_id={{ $work->work_id }}&work_status=9" class="btn btn-success btn-xs btn-block" data-toggle="tooltip" data-placement="bottom" title="कार्य पूर्ण करे"><i class="fa fa-check"></i></a></td>
                                                                <td class="p-0"><a href="{{ route('work-progress.create') }}?work_id={{ $work->work_id }}&work_status=10" class="btn btn-warning btn-xs btn-block" data-toggle="tooltip" data-placement="bottom" title="कार्य निरस्त करे"><i class="fa fa-times"></i></a></td> --}}
                                                                <td class="p-0"><button onclick="openDeleteModal('Are You Sure You Want to Permanently Delete Record ?','{{route('work.show',$work->work_id)}}');" class="btn btn-danger btn-xs btn-block" data-toggle="tooltip" data-placement="bottom" title="कार्य हटाये"><i class="fa fa-trash"></i></button></td>
                                                            </tr>
                                                        </table>

                                                    </td>

                                                </tr>
                                                @endif
                                                </tbody>
                                            </table>


                                        </td>
                                    @endif
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
