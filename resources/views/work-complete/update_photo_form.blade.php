<form action="{{ route('work-complete.update-photo', $workComplete->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-sm-12">
            <label for="file" class="col-form-label">छायाचित्र चुनें <span style="color: red">&nbsp;*</span></label>
            <input class="form-control" type="file" id="file" name="file" accept=".png,.jpg,.jpeg,.gif,.webp,.pdf" required>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            <input type="submit" id="submit" class="btn btn-lg btn-success pull-right" value="अद्यतन करें">
        </div>
    </div>
</form>
