<form method="post" action="{{ isset($workType)?route('work_types.update',$workType->work_type_id): route('work_types.store') }}">
    @if(isset($workType))
        @method('PUT')
    @endif
    @csrf
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">Work Type Name : <span class="text-danger">*</span></span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="name" class="form-control" placeholder="Enter Work Type Name" value="{{ isset($workType)?$workType->work_type_name:"" }}" required>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">कार्य श्रेणी :</span>
        </div>
        <div class="col-sm-8">
            <select name="work_category_id" class="form-control form-select">
                <option value="">-- कार्य श्रेणी चुनें --</option>
                @foreach(get_work_categories() as $category)
                    <option value="{{ $category->work_category_id }}"
                        {{ echo_selected($category->work_category_id == (isset($workType) ? $workType->work_category_id : '')) }}>
                        {{ $category->work_category_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

<div class="row form-group">
    <table class="table table-bordered p-0 m-0">
        <thead>
        <tr>
            <th>Stage Name</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($workType))
            @foreach($workType->work_stages as $stage)

                <tr>
                    <td class="p-0">
                        <input type="hidden" name="work_stage_id[]" value="{{ $stage->work_type_stage_id  }}">
                        <input type="text" name="work_stages[]" class="form-control" placeholder="Enter Stage Name" value="{{ $stage->work_type_stage_name }}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-xs btn-danger" onclick="removeStageRow(this)"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-xs btn-success" onclick="addStageRow(this)"><i class="fa fa-plus"></i> Add New</button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="p-0"><input type="text" name="work_stages[]" class="form-control" placeholder="Enter Stage Name"></td>
                <td>
                    <button type="button" class="btn btn-xs btn-danger" onclick="removeStageRow(this)"><i class="fa fa-trash"></i></button>
                    <button type="button" class="btn btn-xs btn-success" onclick="addStageRow(this)"><i class="fa fa-plus"></i> Add New</button>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

<div class="form-group">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    @if(isset($workType))
        <button type="submit" class="btn btn-primary pull-right">Update WorkType</button>
    @else
        <button type="submit" class="btn btn-success pull-right">Add Work Type</button>
    @endif
</div>
</form>


