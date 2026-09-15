
   <?php
       if(isset($work))
           if ( $work->tender_id) {
            $tender = $work->tender;
        }
   ?>


<form action="{{ isset($tender) ? route('tender.update', $tender->tender_id) : route('tender.store') }}" method="post"
    enctype="multipart/form-data">
    @if (isset($tender))
        @method('PUT')
    @endif
    @csrf
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="tender_no" class="col-form-label">निविदा संख्या <span style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="text" id="tender_no" name="tender_no"
                value="{{ isset($tender) ? $tender->tender_no : '' }}"
                @error('tender_no')
            form-control-danger
        @enderror {{  isset($edit)?'':(( $work->tender_id)? 'readonly':'required') }}>
            @error('tender_no')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
            <input type="hidden" name="work_id" id="work_id" value="{{ $work->work_id
             }}">
            <input type="hidden" name="work_status" id="work_status" value="{{ $work_status->work_status_id }}">
            @if ( $work->tender_id)
            <input type="hidden" name="tender_id"  value="{{ $work->tender_id }}">
            @endif
        </div>
        <div class="col-sm-3">
            <label for="tender_release_date" class="col-form-label">निविदा की तिथि<span
                    style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="date" id="tender_release_date" name="tender_release_date"
                value="{{ isset($tender) ? $tender->tender_release_date : '' }}"
                @error('tender_release_date')
                form-control-danger
            @enderror {{  isset($edit)?'':(( $work->tender_id)? 'readonly':'required') }}>
            @error('tender_release_date')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-3">
            <label for="tender_opening_date" class="col-form-label">निविदा खोलने की तिथि <span
                    style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="date" id="tender_opening_date" name="tender_opening_date"
                value="{{ isset($tender) ? $tender->tender_opening_date : '' }}"
                @error('tender_opening_date')
                form-control-danger
            @enderror {{  isset($edit)?'':(( $work->tender_id)? 'readonly':'required') }}>
            @error('tender_opening_date')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="work_order_date" class="col-form-label">वर्क ऑर्डर की तारीख <span
                    style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="date" id="work_order_date" name="work_order_date"
                value="{{ isset($tender) ? $tender->tender_opening_date : '' }}"
                @error('work_order_date')
            form-control-danger
        @enderror {{  isset($edit)?'':(( $work->tender_id)? 'required':'readonly') }}>
            @error('work_order_date')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-3">
            <label for="file" class="col-form-label">दस्तावेज़ अपलोड करें <span
                style="color: red">&nbsp; (Images/Pdf)</span></label>
            <input class="form-control" type="file" id="file" name="file" accept=".png, .jpg, .jpeg, .pdf" />
        </div>
        <div class="col-sm-3">
            <label for="remark" class="col-form-label">टिप्पणी</label>
            <textarea name="remark" id="remark" cols="30" rows="2">{{ isset($tender) ? $tender->remark : '' }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            <input type="submit" value="{{ isset($tender) ? 'Update' : 'Submit' }}" id="submit"
                class="btn btn-primary pull-right">
        </div>
    </div>
</form>
