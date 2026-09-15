<form method="post" action="{{ isset($user)?route('users.update',$user->user_id): route('users.store') }}">
    @if(isset($user))
        @method('PUT')
    @endif
    @csrf
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">Login ID : <span class="text-danger">*</span></span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="login_id" class="form-control" placeholder="Enter Login ID" value="{{ isset($user)?$user->login_id:"" }}" required>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">User Name : <span class="text-danger">*</span></span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{ isset($user)?$user->name:"" }}" required>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">Email :</span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="email" class="form-control" placeholder="Enter Email Address" value="{{ isset($user)?$user->email:"" }}">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">Mobile :</span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile Number" value="{{ isset($user)?$user->mobile:"" }}">
        </div>
    </div>
    @if(! isset($user))
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">Password : <span class="text-danger">*</span></span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="password" class="form-control" placeholder="Enter Password" value="{{ isset($user)?$user->password:"" }}" required>
        </div>
    </div>
    @endif
    @if(isset($user))
            <div class="row form-group">
                <div class="col-sm-4">
                    <span class="pull-right">Password :</span>
                    <br>
                    <small class="text-success text-center ">Fill If you want to change the password</small>
                </div>
                <div class="col-sm-8">
                    <input type="text" name="password" class="form-control" placeholder="Enter Password" value="">
                </div>
            </div>
    @endif


    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">Office : <span class="text-danger">*</span></span>
        </div>
        <div class="col-sm-8">
            <select name="office" class="form-control form-select" required>
                <option value="">-- Select Office --</option>
                @foreach(get_offices() as $list)
                <option value="{{ $list->office_id }}" {{ echo_selected($list->office_id == (isset($user)?$user->office_id:''))}}>{{$list->office_name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        @if(isset($user))
            <button type="submit" class="btn btn-primary pull-right">Update User</button>
        @else
            <button type="submit" class="btn btn-success pull-right">Add User</button>
        @endif
    </div>
</form>
