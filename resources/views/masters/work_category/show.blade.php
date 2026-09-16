<div class="row">
    <div class="col-sm-6"><span class="pull-right">श्रेणी का नाम :</span></div>
    <div class="col-sm-6"><b>{{ $category->work_category_name }}</b></div>
</div>
<div class="row form-group">
    <div class="col-sm-6">
        <span class="pull-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">बंद करें</button>
        </span>
    </div>
    <div class="col-sm-6">
        <form action="{{ route('work_category.destroy', $category->work_category_id) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">हटाएं</button>
        </form>
    </div>
</div>
