@extends('layout.app')
@section('title', 'कार्य प्रगति | Nirmaan')
@section('content')
    @include('layout.includes.page_header', ['icon' => 'home', 'title' => 'कार्य प्रगति'])
    <!-- Main content -->
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12">

                <div class="panel panel-primary" hidden>
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4> कार्य प्रगति </h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form onchange="this.submit()">
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="work_id" class="col-form-label">कार्य<span
                                            style="color: red">&nbsp;*</span></label>
                                    <select name="work_id" id="work_id" class="form-control form-select">
                                        <option value="">-- Select Work Id --</option>
                                        @foreach ($work_list as $item)
                                            <option value="{{ $item->work_id }}"
                                                {{ $work ? ($work->work_id == $item->work_id ? 'selected' : '') : '' }}>
                                                {{ $item->work_id }} - {{ $item->work_name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-sm-3">
                                    <label for="work_status" class="col-form-label">कार्य की स्थिति <span
                                            style="color: red">&nbsp;*</span></label>
                                    <select name="work_status" id="work_status" class="form-control">
                                        <option value="">-- Select Status --</option>
                                        @foreach ($work_status_list as $item)
                                            <option value="{{ $item->work_status_id }}"
                                                {{ $work_status ? ($work_status->work_status_id == $item->work_status_id ? 'selected' : '') : '' }}
                                                {{ $work ? ($work->work_status == $item->work_status_id ? 'selected' : '') : '' }}>
                                                {{ $item->work_status_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($work_status)
                    @if ($work_status->work_status_id == 2 || $work_status->work_status_id == 3)
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4> तकनीकी स्वीकृति </h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                @include('Technical-Sanction.tsform', [
                                    'work' => $work,
                                    'work_status' => $work_status,
                                ])
                            </div>
                        </div>
                    @endif
                    @if ($work_status->work_status_id == 4 || $work_status->work_status_id == 5)
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4> प्रशासकीय  स्वीकृति </h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                @include('Administrative-Sanction.asform', [
                                    'work' => $work,
                                    'work_status' => $work_status,
                                ])
                            </div>
                        </div>
                    @endif
                    @if ($work_status->work_status_id == 6 || $work_status->work_status_id == 7)
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4> निविदा </h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                @include('Tender.tenderform', [
                                    'work' => $work,
                                    'work_status' => $work_status,
                                ])
                            </div>
                        </div>
                    @endif
                    @if ($work_status->work_status_id == 8)
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4> अनुबंध पूर्ण </h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            @include('work-complete.agreement', [
                                'work' => $work,
                                'work_status' => $work_status,
                                'stages' => $stages,
                            ])
                        </div>
                    </div>
                @endif
                    @if ($work_status->work_status_id == 9)
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4> कार्य प्रगति </h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                @include('Work-Progress.workprogressform', [
                                    'work' => $work,
                                    'work_status' => $work_status,
                                    'stages' => $stages,
                                ])
                            </div>
                        </div>
                    @include('Work-Progress.work_progress_list', ['work' => $work])
                    @endif
                    {{-- @if ($work_status->work_status_id == 10)
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4> कार्य समापन </h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                @include('work-complete.complete', [
                                    'work' => $work,
                                    'work_status'=>$work_status,
                                ])
                            </div>
                        </div>
                    @endif --}}
                    {{-- @if ($work_status->work_status_id == 11)
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4> कार्य अस्वीकृति </h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                @include('work-complete.reject', [
                                    'work' => $work,
                                    'work_status'=>$work_status,
                                ])
                            </div>
                        </div>
                    @endif --}}
                @endif

            </div>
        </div>
    </div>

@endsection
