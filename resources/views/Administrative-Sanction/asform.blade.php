<?php
if ($work->as_id) {
         $administrativeSanction = $work->administrative_sanction;
     }
?>

<form
    action="{{ isset($administrativeSanction) ? route('administrative-sanction.update', $administrativeSanction->as_id) : route('administrative-sanction.store') }}"
    method="post" enctype="multipart/form-data">
    @if (isset($administrativeSanction))
        @method('PUT')
    @endif
    @csrf
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="as_no" class="col-form-label">सरकार/जिला द्वारा ए.एस</label>
            <div class="i-check">
                <input tabindex="1" type="radio" id="square-radio-1" name="as_by" value="Govt">
                <label for="square-radio-1">Govt</label>&emsp;
                <input tabindex="2" type="radio" id="square-radio-2" name="as_by" value="District" checked>
                <label for="square-radio-2">District</label>
                <input type="hidden" name="work_id" id="work_id" value="{{ $work->work_id
                }}" {{  isset($edit)?'':(( $work->as_id)? 'readonly':'required') }}>
               <input type="hidden" name="work_status" id="work_status" value="{{ $work_status->work_status_id }}" {{  isset($edit)?'':(( $work->as_id)? 'readonly':'required') }}>
               @if ( $work->as_id)
               <input type="hidden" name="as_id"  value="{{ $work->as_id }}">
               @endif
            </div>
        </div>
        <div class="col-sm-3">
            <label for="as_no" class="col-form-label">प्रशासकीय  स्वीकृति संख्या <span style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="text" placeholder="AS No" id="as_no" name="as_no"
                value="{{ isset($administrativeSanction) ? $administrativeSanction->as_no : '' }}"
                @error('as_no')
                                            form-contol-danger
                                        @enderror
                                        {{  isset($edit)?'':(( $work->as_id)? 'readonly':'required') }}>
            @error('as_no')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-3">
            <label for="as_submission_Date" class="col-form-label">प्रशासकीय स्वीकृति जमा करने की तिथि <span
                    style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="date" id="as_submission_Date" name="as_submission_Date"
                value="{{ isset($administrativeSanction) ? $administrativeSanction->submission_date : '' }}"
                @error('as_submission_Date')
                                            form-control-danger
                                        @enderror
                                        {{  isset($edit)?'':(( $work->as_id)? 'readonly':'required') }}>
            @error('as_no')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="as_approval_date" class="col-form-label">स्वीकृति तिथि </label>
            <input class="form-control" type="date" id="as_approval_date" name="as_approval_date"
                value="{{ isset($administrativeSanction) ? $administrativeSanction->approval_date : '' }}"
                @error('as_approval_date')
                                            form-control-danger
                                        @enderror {{  isset($edit)?'':(( $work->as_id)? 'required':'readonly') }}>
            @error('as_approval_date')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-3">
            <label for="as_amount" class="col-form-label">प्रशासकीय  स्वीकृति की राशि <span style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="number" step="any" id="as_amount" name="as_amount"
                value="{{ isset($administrativeSanction) ? $administrativeSanction->as_amount : '' }}"
                @error('as_amount')
                                            form-control-danger
                                        @enderror
                                        {{  isset($edit)?'':(( $work->as_id)? 'readonly':'required') }}>
            @error('as_amount')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-3">
            <label for="file"
                class="col-form-label">{{ isset($administrativeSanction) ? 'फ़ाइल बदलें' : 'Upload File' }}<span
                style="color: red">&nbsp; (Images/Pdf)</span></label>
            <input class="form-control" type="file" id="file" name="file" accept=".png, .jpg, .jpeg, .pdf" />
        </div>
        <div class="col-sm-3">
            <label for="remark" class="col-form-label">टिप्पणी</label>
            <textarea name="remark" id="remark" class="form-control" rows="2">{{ isset($administrativeSanction) ? $administrativeSanction->remark : '' }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            <input type="submit" value="{{ isset($administrativeSanction) ? 'Update' : 'Submit' }}" id="submit"
                class="btn btn-primary pull-right">
        </div>
    </div>
</form>
