<form method="post" action="{{ isset($village) ? route('village.update', $village->village_id) : route('village.store') }}">
    @if(isset($village))
        @method('PUT')
    @endif
    @csrf
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">ग्राम का नाम :</span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="village_name" class="form-control" placeholder="ग्राम का नाम दर्ज करें"
                   value="{{ isset($village) ? $village->village_name : '' }}" required>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">ग्राम (अंग्रेज़ी) :</span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="village_name_en" class="form-control" placeholder="Village name in English"
                   value="{{ isset($village) ? $village->village_name_en : '' }}">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4">
            <span class="pull-right">ग्राम पंचायत :</span>
        </div>
        <div class="col-sm-8">
            <select name="grampanchayat_id" class="form-control form-select" required>
                <option value="">-- ग्राम पंचायत चुनें --</option>
                @foreach(get_grampanchayats() as $list)
                    <option value="{{ $list->grampanchayat_id }}"
                        {{ echo_selected($list->grampanchayat_id == (isset($village) ? $village->grampanchayat_id : '')) }}>
                        {{ $list->grampanchayat_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-danger" data-dismiss="modal">बंद करें</button>
        @if(isset($village))
            <button type="submit" class="btn btn-primary pull-right">अद्यतन करें</button>
        @else
            <button type="submit" class="btn btn-success pull-right">ग्राम जोड़ें</button>
        @endif
    </div>
</form>
