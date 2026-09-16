<form action="{{ route('work-progress-images.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="work_id" value="{{ $work->work_id }}">

    <div class="form-group row">
        <div class="col-sm-12">
            <label for="mb_stages" class="col-form-label">एमबी स्टेज <span style="color: red">&nbsp;*</span></label>
            <select name="mb_stages" id="mb_stages" class="form-control" required>
                <option value="">-- स्टेज चुनें --</option>
                @foreach($work->work_type->work_stages as $stage)
                    <option value="{{ $stage->work_type_stage_id }}">{{ $stage->work_type_stage_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-12">
            <label for="files" class="col-form-label">छायाचित्र अपलोड करें <span style="color: red">&nbsp;*</span>
                (Image/Pdf, एक से अधिक चुन सकते हैं)</label>
            <input class="form-control" type="file" id="files" name="files[]" accept=".png,.jpg,.jpeg,.gif,.webp,.pdf" multiple required>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-12">
            <input type="submit" id="submit" class="btn btn-lg btn-success pull-right" value="अपलोड करें">
        </div>
    </div>
</form>
