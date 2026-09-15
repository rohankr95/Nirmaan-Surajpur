@extends('layout.app')
@section('title','ग्राम पंचायत वार रिपोर्ट | निर्माण')
@section('content')
    @include('layout.includes.page_header',['icon'=>'home','text'=>'Dashboard'])
    <!-- Main content -->
    <div class="content">

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4> ग्राम पंचायत  सूची </h4>
                        </div>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-bordered table-hover table-striped table-condensed text-center m-0" id="dataTableExample2">
                            <thead class="bg-info">
                                <tr>
                                    <th width="5%">क्र.</th>
                                    <th class="text-left">ग्राम पंचायत का नाम</th>
                                    <th>कुल कार्य</th>
                                    @foreach(get_work_statuses() as $status)
                                    <th>{{ $status->work_status_name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            @php $sl=1; @endphp
                            @foreach($gp_data as $gp)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td class="text-left"><a href="{{ route('reports.villege-wise')."?grampanchayat=".$gp->grampanchayat_id }}">{{ $gp->grampanchayat_name }}</a></td>
                                    <td class="count"> {!!  ($data = $gp->total_works)?'<a href="'.route("reports.works").'?grampanchayat='.$gp->grampanchayat_id.'">'.$data.'</a>':'-' !!} </td>
                                    @foreach(get_work_statuses() as $status)
                                        <td class="count">{!!  ($data = $gp->work_stage_data[$status->work_status_id])?'<a href="'.route("reports.works").'?grampanchayat='.$gp->grampanchayat_id.'&status='.$status->work_status_id.'">'.$data.'</a>':'-' !!}</td>
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
