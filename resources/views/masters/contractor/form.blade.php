<form method="post" action="{{ isset($contractor) ? route('contractor.update', $contractor->contractor_id) : route('contractor.store') }}">
    @if(isset($contractor))
        @method('PUT')
    @endif
    @csrf
    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">ठेकेदार का नाम :</span></div>
        <div class="col-sm-8">
            <input type="text" name="contractor_name" class="form-control" placeholder="ठेकेदार / फर्म का नाम"
                   value="{{ isset($contractor) ? $contractor->contractor_name : '' }}" required>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">संपर्क व्यक्ति :</span></div>
        <div class="col-sm-8">
            <input type="text" name="contact_person" class="form-control" placeholder="संपर्क व्यक्ति"
                   value="{{ isset($contractor) ? $contractor->contact_person : '' }}">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">मोबाइल :</span></div>
        <div class="col-sm-8">
            <input type="text" name="mobile" class="form-control" placeholder="मोबाइल नंबर"
                   value="{{ isset($contractor) ? $contractor->mobile : '' }}">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">पंजीयन क्रमांक :</span></div>
        <div class="col-sm-8">
            <input type="text" name="registration_no" class="form-control" placeholder="पंजीयन क्रमांक"
                   value="{{ isset($contractor) ? $contractor->registration_no : '' }}">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4"><span class="pull-right">पता :</span></div>
        <div class="col-sm-8">
            <input type="text" name="address" class="form-control" placeholder="पता"
                   value="{{ isset($contractor) ? $contractor->address : '' }}">
        </div>
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-danger" data-dismiss="modal">बंद करें</button>
        @if(isset($contractor))
            <button type="submit" class="btn btn-primary pull-right">अद्यतन करें</button>
        @else
            <button type="submit" class="btn btn-success pull-right">ठेकेदार जोड़ें</button>
        @endif
    </div>
</form>
