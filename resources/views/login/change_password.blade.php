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
                            <h4> User </h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">

                            <div class="col-sm-12 col-md-7">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h4> Update Password </h4>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <form action="{{ route('update-password',$user->user_id) }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                  <label for="old_password" class="col-form-label">Old Pasword </label>
                                                  <input type="text" name="old_password" class="form-control">
                                                  <label for="new_password" class="col-form-label">New Password </label>
                                                  <input type="text" name="new_password" class="form-control">
                                                  <label for="confirm_password" class="col-form-label">Confirm Pasword </label>
                                                  <input type="text" name="confirm_password" class="form-control">
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
                </div>
            </div>
        </div>
    </div>
        @endsection
