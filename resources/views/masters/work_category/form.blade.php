<form method="post" action="{{ isset($category) ? route('work_category.update', $category->work_category_id) : route('work_category.store') }}">
    @if(isset($category))
        @method('PUT')
    @endif
    @csrf
    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">श्रेणी का नाम :</span></div>
        <div class="col-sm-8">
            <input type="text" name="work_category_name" class="form-control" placeholder="जैसे पेयजल आपूर्ति"
                   value="{{ isset($category) ? $category->work_category_name : '' }}" required>
        </div>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-danger" data-dismiss="modal">बंद करें</button>
        @if(isset($category))
            <button type="submit" class="btn btn-primary pull-right">अद्यतन करें</button>
        @else
            <button type="submit" class="btn btn-success pull-right">श्रेणी जोड़ें</button>
        @endif
    </div>
</form>
