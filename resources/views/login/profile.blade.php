@extends('layout.app')
{{-- @section('title', 'Dashboard') --}}
@section('content')
    @include('layout.includes.page_header', ['icon' => 'home', 'text' => 'Dashboard'])
    <!-- Main content -->
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">

                    <div class="panel-body p-0">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-header-menu">
                                            <i class="fa fa-bars"></i>
                                        </div>
                                        <div class="card-header-headshot"></div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-content-member">
                                            <h4 class="m-t-0">{{ $user->name }}</h4>
                                            <small class="text-warning">{{ $user->designation }}</small>

                                        </div>
                                        <div class="card-content-languages p-0">
                                            <table class="table table-bordered table-hover mb-0">
                                                <tbody>
                                                    <tr><td>Login ID</td><th>{{ $user->login_id }}</th></tr>
                                                    <tr><td>Role</td><th>{{ $user->role->role_name }}</th></tr>
                                                    <tr><td>Office</</td><th>{{ $user->office->office_name }}</th></tr>
                                                    <tr><td>LandLine No</td><th>{{ $user->landline }}</th></tr>
                                                    <tr><td>Mobile No</td><th>{{ $user->mobile }}</th></tr>
                                                    <tr><td>Email</td><th>{{ $user->email }}</th></tr>
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4> Update Profile </h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('update-profile',$user->user_id) }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label> Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                                    <br>
                                    <label> Designation </label>
                                    <input type="text" name="designation" value="{{ $user->designation }}" class="form-control">

                                    <br>
                                    <label> Landline Number </label>
                                    <input type="text" name="landline" value="{{ $user->landline }}" class="form-control">

                                    <br>
                                    <label> Email </label>
                                    <input type="text" name="email" value="{{ $user->email }}" class="form-control">

                                    <br>
                                    <label> Mobile Number </label>
                                    <input type="text" name="mobile" value="{{ $user->mobile }}" class="form-control">


                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input type="submit" id="submit" class="btn btn-lg btn-primary pull-left" value="Update">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        @endsection
