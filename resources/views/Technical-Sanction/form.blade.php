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
                            <h4> Technical Sanction </h4>
                        </div>
                    </div>
                    <div class="panel-body">
                     @include('Technical-Sanction.tsform',isset($work)?['work'=>$work]:[])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
