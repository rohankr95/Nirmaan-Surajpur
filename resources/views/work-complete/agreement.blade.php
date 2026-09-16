<form action="{{ route('work-agreement') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="work_id" id="work_id" value="{{ $work->work_id }}">
    <input type="hidden" name="work_status" id="work_status" value="{{ $work_status->work_status_id }}">

    <div class="form-group row">
        <div class="col-sm-4">
            <label for="agreement_date" class="col-form-label">अनुबंध पूर्ण होने की तिथि <span style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="date" id="agreement_date" name="agreement_date" required>
        </div>
        <div class="col-sm-4">
            <label for="work_order_no" class="col-form-label">कार्य आदेश क्रमांक</label>
            <input class="form-control" type="text" id="work_order_no" name="work_order_no" placeholder="कार्य आदेश क्रमांक">
        </div>
        <div class="col-sm-4">
            <label for="work_order_date" class="col-form-label">कार्य आदेश दिनांक</label>
            <input class="form-control" type="date" id="work_order_date" name="work_order_date">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-4">
            <label for="work_order_amount" class="col-form-label">कार्य आदेश राशि (₹)</label>
            <input class="form-control" type="number" step="0.01" min="0" id="work_order_amount" name="work_order_amount" placeholder="राशि">
        </div>
        <div class="col-sm-4">
            <label for="contractor_id" class="col-form-label">ठेकेदार / ग्रामपंचायत</label>
            <select name="contractor_id" id="contractor_id" class="form-control form-select">
                <option value="">-- ठेकेदार चुनें --</option>
                @foreach(get_contractors() as $contractor)
                    <option value="{{ $contractor->contractor_id }}">{{ $contractor->contractor_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-4">
            <label for="agreement_file" class="col-form-label">दस्तावेज़ अपलोड करें</label>
            <input class="form-control" type="file" id="agreement_file" name="file" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-12">
            <label for="remark" class="col-form-label">टिप्पणी</label>
            <textarea name="remark" id="remark" cols="20" rows="1" class="form-control"></textarea>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-12">
            <input type="submit" id="submit" class="btn btn-lg btn-success pull-right" value="सहेजें">
        </div>
    </div>
</form>
