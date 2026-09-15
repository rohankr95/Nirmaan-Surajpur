<div class="row">
    <div class="col-sm-6">
        <span class="pull-right">Login ID :</span>
    </div>
    <div class="col-sm-6">
        <b>{{ $user->login_id }}</b>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <span class="pull-right">User Name :</span>
    </div>
    <div class="col-sm-6">
        <b>{{ $user->name }}</b>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <span class="pull-right">User Email :</span>
    </div>
    <div class="col-sm-6">
        <b>{{ $user->email }}</b>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <span class="pull-right">User Mobile :</span>
    </div>
    <div class="col-sm-6">
        <b>{{ $user->mobile }}</b>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <span class="pull-right">Office :</span>
    </div>
    <div class="col-sm-6">
        <b>{{ $user->office->office_name }}</b>
    </div>
</div>
{{--<div class="row form-group">--}}
{{--    <div class="col-sm-6">--}}
{{--        <span class="pull-right">Department :</span>--}}
{{--    </div>--}}
{{--    <div class="col-sm-6">--}}
{{--        <b>{{ $user->office->department->department_name }}</b>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="row form-group">
    <div class="col-sm-12">
        <center><h4 class="text-danger">You don't have permission to delete users.</h4></center>
    </div>
    <div class="col-sm-12">
        <span class="pull-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </span>
    </div>
{{--    <div class="col-sm-6">--}}
{{--        <form action="{{ route('users.destroy',$user->user_id) }}" method="post">--}}
{{--            @csrf--}}
{{--            @method('DELETE')--}}
{{--            <button type="submit" class="btn btn-danger">Delete</button>--}}
{{--        </form>--}}
{{--    </div>--}}
</div>
