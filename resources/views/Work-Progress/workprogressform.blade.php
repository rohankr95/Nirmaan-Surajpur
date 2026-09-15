{{-- @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}

<div class="form-group row">
    <label for="CWorkStatus" class="col-sm-3 col-form-label"> <strong>कार्य (आईडी और नाम)</strong> </label>
    <div class="col-sm-9">
        <p><strong>{{ "($work->work_id) $work->work_name" }}</strong></p>
    </div>
</div>
<div class="form-group row">
    <label for="CWorkStatus" class="col-sm-3 col-form-label">कार्य की वर्तमान स्थिति <span
            style="color: red">&nbsp;*</span></label>
    <div class="col-sm-9">
        <select name="CWorkStatus" id="CWorkStatus" class="form-control mb-20">
            <option value="0">--वर्तमान स्थिति चुने--</option>
            <option value="1">कार्य प्रगति पर</option>
            <option value="2">कार्य पूर्ण</option>
            <option value="3">कार्य बंद</option>
            <option value="4">कार्य निरस्त</option>
        </select>
    </div>
</div>
<hr>
<form
    action="{{ isset($workProgress) ? route('work-progress.update', $workProgress->wp_id) : route('work-progress.store') }}"
    method="post" enctype="multipart/form-data" id="wpStagesForm">
    @if (isset($workProgress))
        @method('PUT')
    @endif
    @csrf
    <div class="form-group row">
        {{-- <div class="col-sm-12">
            @include('Work-Progress.work_progress_list', ['work' => $work])
        </div> --}}
        <div class="col-sm-3"> 
            <label for="mb_stages" class="col-form-label">एमबी स्टेज <span style="color: red">&nbsp;*</span></label>
            <a href="{{ route('work_types.index') }}" target="_blank">
            <button type="button" class="btn btn-labeled btn-warning m-b-5">
                <span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Add Stages
            </button>
           </a>
            <select name="mb_stages" id="mb_stages" class="form-control"
                @error('mb_stages')
            form-control-danger
        @enderror>
                <option value="">-- Select Stages --</option>
                @foreach ($work->work_type->work_stages as $item)
                    <option value="{{ $item->work_type_stage_id }}"
                        {{ isset($workProgress) ? ($workProgress->mb_stages_id == $item->work_type_stage_id ? 'selected' : '') : '' }}>
                        {{ $item->work_type_stage_name }}</option>
                @endforeach
            </select>
            @error('mb_stages')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
            <input type="hidden" name="work_id" id="work_id" value="{{ $work->work_id }}">
            <input type="hidden" name="work_status" id="work_status" value="{{ $work_status->work_status_id }}">
            @if ($work->wp_id)
                <input type="hidden" name="wp_id" value="{{ $work->wp_id }}">
            @endif
        </div>
        <div class="col-sm-3">
            <label for="estimated_completion_date" class="col-form-label">अनुमानित समापन की तिथि</label>
            <input class="form-control" type="date" id="estimated_completion_date" name="estimated_completion_date"
                value="{{ isset($workProgress) ? $workProgress->estimated_completion_date : '' }}"
                @error('estimated_completion_date')
                  form-control-danger
              @enderror
                >
            @error('estimated_completion_date')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-3">
            <label for="expenditure_amount" class="col-form-label">व्यय राशि</label>
            <input class="form-control" type="number" step="any" id="expenditure_amount" name="expenditure_amount"
                value="{{ isset($workProgress) ? $workProgress->expenditure_amount : '' }}">
        </div>
        <div class="col-sm-3">
            <label for="file"
                class="col-form-label">{{ isset($workProgress) ? 'Change Images/Bill' : 'दस्तावेज/बिल अपलोड करें' }}&nbsp;<span
                style="color: red">*</span>
               (Image/Pdf)</label>
            <input class="form-control" type="file" id="file" name="file" accept=".png, .jpg, .jpeg, .pdf" required/>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="description" class="col-form-label">विवरण</label>
            <textarea name="description" id="description" cols="27" rows="2">{{ isset($workProgress) ? $workProgress->description : '' }}</textarea>
        </div>
    </div><hr>
    <div class="form-group row">
        <div class="col-sm-12">
            <input type="submit" value="{{ isset($workProgress) ? 'Update' : 'Save Stage' }}" id="submit"
                class="btn btn-primary pull-right">
        </div>
    </div>
</form>

@include('work-complete.closed')
@include('work-complete.complete')
@include('work-complete.reject')
