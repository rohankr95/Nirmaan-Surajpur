<div class="row">
    <div class="col-sm-6">
        <span class="pull-right">ग्राम का नाम :</span>
    </div>
    <div class="col-sm-6">
        <b>{{ $village->village_name }}</b>
    </div>
</div>
<div class="row form-group">
    <div class="col-sm-6">
        <span class="pull-right">ग्राम पंचायत :</span>
    </div>
    <div class="col-sm-6">
        <b>{{ $village->grampanchayat->grampanchayat_name ?? '—' }}</b>
    </div>
</div>
<div class="row form-group">
    <div class="col-sm-6">
        <span class="pull-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">बंद करें</button>
        </span>
    </div>
    <div class="col-sm-6">
        <form action="{{ route('village.destroy', $village->village_id) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">हटाएं</button>
        </form>
    </div>
</div>
