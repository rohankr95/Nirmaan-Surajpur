
  <?php
   if(isset($work)){
       if ($work->ts_id) {
            $technicalSanction = $work->technical_sanction;
        }
   }
   $isEditMode = isset($editMode);
   ?>
<form
    action="{{ isset($technicalSanction) ? route('technical-sanction.update', $technicalSanction->ts_id) : route('technical-sanction.store') }}"
    method="post" enctype="multipart/form-data">
    @if (isset($technicalSanction))
        @method('PUT')
    @endif
    @csrf
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="ts_no" class="col-form-label">तकनीकी स्वीकृति नंबर<span style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="text" placeholder="Ts No" id="ts_no" name="ts_no"
                @error('ts_no')
            form-control-danger
            @enderror
                value="{{ isset($technicalSanction) ? $technicalSanction->ts_no : '' }}" {{   isset($edit)?'':(( $work->ts_id)? 'readonly':'required') }}>
            @error('ts_no')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror

            <input type="hidden" name="work_id" id="work_id" value="{{ $work->work_id
            }}">
           <input type="hidden" name="work_status" id="work_status" value="{{ $work_status->work_status_id }}">
           @if ( $work->ts_id)
           <input type="hidden" name="tender_id"  value="{{ $work->ts_id }}">
           @endif


        </div>
        <div class="col-sm-3">
            <label for="submission_date" class="col-form-label">तकनीकी स्वीकृति जमा करने की दिनांक <span
                    style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="date" id="submission_date" name="submission_date"
                @error('submission_date')
        form-control-danger
        @enderror
                value="{{ isset($technicalSanction) ? $technicalSanction->submission_date : '' }}" {{   isset($edit)?'':(( $work->ts_id)? 'readonly':'required') }}>
            @error('submission_date')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-3">
            <label for="ts_amount" class="col-form-label">तकनीकी स्वीकृति की राशि</label>
            <input class="form-control" type="number" step="any" placeholder="Ts Amount" id="ts_amount" name="ts_amount"
                value="{{ isset($technicalSanction) ? $technicalSanction->ts_amount : '' }}" {{ isset($edit)?'':(( $work->ts_id)? 'readonly':'required') }}>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="approval_date" class="col-form-label">तकनीकी स्वीकृति स्वीकृति दिनांक</label>
            <input class="form-control" type="date" id="approval_date" name="approval_date"
                @error('approval_date')
              form-control-danger
          @enderror 
                value="{{ isset($technicalSanction) ? $technicalSanction->approval_date : '' }}" {{  isset($edit)?'':(( $work->ts_id)? 'required':'readonly') }}>
            @error('approval_date')
                <div class="form-feedback text-danger"> {{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-3">  
            <label for="file"
                class="col-form-label">{{ isset($technicalSanction) ? 'दस्तावेज बदलें' : 'दस्तावेज अपलोड करें' }}<span
                style="color: red">&nbsp; (Images/Pdf)</span></label>
            <input class="form-control" type="file" placeholder="Approval File" id="file" name="file" accept=".png, .jpg, .jpeg, .pdf" required/>
        </div>
        <div class="col-sm-3">
            <label for="remark" class="col-form-label">टिप्पणी</label>
            <textarea name="remark" id="remark" cols="30" rows="2">{{ isset($technicalSanction) ? $technicalSanction->remark : '' }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            <input type="submit" value="{{ isset($technicalSanction) ? 'Update' : 'Submit' }}" id="submit"
                class="btn btn-primary pull-right">
        </div>
    </div>
</form>
