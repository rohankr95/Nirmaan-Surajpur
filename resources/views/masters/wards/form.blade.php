<form method="post" action="{{ isset($ward)?route('wards.update',$ward->ward_id): route('wards.store') }}">
    @if(isset($ward))
        @method('PUT')
    @endif
    @csrf
    
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">City :</span>
        </div>
        <div class="col-sm-8">
            <input type="hidden" name="city_id" class="form-control" placeholder="Enter Ward Name" value="{{ isset($ward)?$ward->city_id:"" }}">
            <input type="hidden" name="ward_id" class="form-control" placeholder="Enter Ward Name" value="{{ isset($ward)?$ward->ward_id:"" }}">
            <input type="text" name="city_name" class="form-control" placeholder="Enter Ward Name" value="{{ isset($ward)?$ward->city->city_name:"" }}" disabled>
            {{-- <select name="city_wardForm" class="form-control form-select" disabled>
                <option value="">-- Select city --</option>
                @foreach(get_city() as $city)
                <option value="{{ $city->city_id }}" {{ echo_selected($city->city_id== (isset($ward)?$ward->city_id:''))}}>
                    {{ $city->city_name }}</option>
                @endforeach
            </select> --}}
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">Ward Name :</span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="ward_name" class="form-control" placeholder="Enter Ward Name" value="{{ isset($ward)?$ward->ward_name:"" }}" required>
        </div>
    </div>

<div class="form-group">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    @if(isset($ward))
        <button type="submit" class="btn btn-primary pull-right">Update ward</button>
    @else
        <button type="submit" class="btn btn-success pull-right">Add ward</button>
    @endif
</div>
</form>
