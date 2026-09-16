<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">
            <h4>{{ isset($work) ? 'संपादित करें' : 'कार्य जोड़ें' }} </h4>
        </div>
    </div>
    {{-- <div >
        <img src="{{ asset('images/loader.gif') }}" alt="Loading..." height="200px">
    </div> --}}
    {{-- <div class="icon_box" >
        <i class="hvr-buzz-out fa fa-circle-o-notch"></i>
        <span class="icon-name">circle-o-notch</span>
    </div> --}}

    <div class="icon_box" id="loader" style="display: none;">
        <i class="hvr-buzz-out fa fa-hourglass-2"></i>
        <span class="icon-name"> <span>Please Wait</span></span>
    </div>
    {{-- <button class="btn btn-primary" type="button" id="loader" style="display: none;">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Loading...
    </button> --}}
    <div class="panel-body">
        <form action="{{ isset($work) ? route('work.update', $work->work_id) : route('work.store') }}" method="post">
            @if (isset($work))
                @method('PUT')
            @endif
            @csrf
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="fy" class="col-form-label">वित्तीय वर्ष <span
                            style="color: red">&nbsp;*</span></label>
                    <select class="form-control form-select" name="fy" id="fy"
                        @error('fy') form-control-danger  @enderror required>
                        <option value="">-- वित्तीय वर्ष चुनें --</option>
                        @foreach ($fy as $item)
                            <option value="{{ $item->id }}"
                                {{ isset($work) ? ($item->id == $work->financial_year_id ? 'selected' : '') : '' }}>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('fy')
                        <div class="form-feedback text-danger"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-3">
                    <label for="dp" class="col-form-label">विभाग <span style="color: red">&nbsp;*</span></label>
                    <select class="form-control form-select" name="dp" id="dp"
                        @error('dp') form-control-danger
                  @enderror required>
                        <option value="">-- विभाग चुनें --</option>
                        @foreach ($dp as $item)
                            <option value="{{ $item->department_id }}"
                                {{ isset($work) ? ($work->department_id == $item->department_id ? 'selected' : '') : '' }}>
                                {{ $item->department_name }}</option>
                        @endforeach
                    </select>
                    @error('dp')
                        <div class="form-feedback text-danger"> {{ $message }}</div>
                    @enderror
                </div>
                @if (is_admin())
                    <div class="col-sm-3">
                        <label for="office" class="col-form-label">एजेंसी <span
                                style="color: red">&nbsp;*</span></label>
                        <select name="office" id="office" class="form-control form-select"
                            @error('office')
                      form-control-danger
                  @enderror>
                            <option value="">-- एजेंसी चुनें --</option>
                            @foreach ($office as $item)
                                <option value="{{ $item->office_id }}"
                                    {{ isset($work) ? ($work->office_id == $item->office_id ? 'selected' : '') : '' }}>
                                    {{ $item->office_name }}</option>
                            @endforeach
                        </select>
                        @error('office')
                            <div class="form-feedback text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="col-sm-3">
                    <label for="scheme" class="col-form-label">योजना <span style="color: red">&nbsp;*</span></label>
                    <select name="scheme" id="scheme" class="form-control form-select"
                        @error('scheme') form-control-danger

                  @enderror required>
                        <option value="">-- योजना चुनें --</option>
                        @foreach ($scheme as $item)
                            <option value="{{ $item->scheme_id }}"
                                {{ isset($work) ? ($work->scheme_id == $item->scheme_id ? 'selected' : '') : '' }}>
                                {{ $item->scheme_name }}</option>
                        @endforeach
                    </select>
                    @error('scheme')
                        <div class="form-feedback text-danger"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="location_type" class="col-form-label">स्थान का प्रकार <span
                            style="color: red">&nbsp;*</span></label>
                    <select name="location_type" id="location_type" value="1" class="form-control"
                        onchange="setAtrr();"
                        @error('location_type')
                          form-control-danger
                      @enderror>
                        <option value="">-- स्थान का प्रकार चुनें --</option>
                        @foreach ($locationType as $item)
                            <option value="{{ $item->location_type_id }}"
                                {{ isset($work) ? ($work->location_type_id == $item->location_type_id ? 'selected' : '') : '' }}>
                                {{ $item->location_type_name }}
                            </option>
                        @endforeach
                        @error('location_type')
                            <div class="form-feedback text-danger"> {{ $message }}</div>
                        @enderror
                    </select>
                </div>
                <div class="col-sm-3 urbanType">
                    <label for="city" class="col-form-label">नगर <span style="color: red">&nbsp;*</span></label>
                    <select name="city" id="city" class="form-control form-select">
                        <option value="">-- शहर चुनें --</option>
                        @foreach ($city as $item)
                            <option value="{{ $item->city_id }}">{{ $item->city_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3 urbanType">
                    <label for="ward" class="col-form-label">वार्ड <span style="color: red">&nbsp;*</span></label>
                    <select name="ward" id="ward" class="form-control form-select">
                    </select>
                </div>
                <div class="col-sm-3 ruralType">
                    <label for="block" class="col-form-label">विकासखंड <span
                            style="color: red">&nbsp;*</span></label>
                    <select name="block" id="block" class="form-control form-select">
                        <option value="">-- विकासखंड चुनें --</option>
                        @foreach ($block as $item)
                            <option value="{{ $item->block_id }}">{{ $item->block_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3 ruralType">
                    <label for="gp" class="col-form-label">ग्रामपंचायत <span
                            style="color: red">&nbsp;*</span></label>
                    <select name="gp" id="gp" class="form-control form-select">

                    </select>
                </div>
                <div class="col-sm-3 ruralType">
                    <label for="village" class="col-form-label">ग्राम <span style="color: red">&nbsp;*</span></label>
                    <select name="village" id="village" class="form-control form-select">

                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="work_type" class="col-form-label">कार्य प्रकार <span
                            style="color: red">&nbsp;*</span></label>
                    <select name="work_type" id="work_type" class="form-control form-select"
                        @error('work_type') form-control-danger
                  @enderror required>
                        <option value="">-- कार्य प्रकार चुनें --</option>
                        @foreach ($workType as $item)
                            <option value="{{ $item->work_type_id }}"
                                {{ isset($work) ? ($work->work_type_id == $item->work_type_id ? 'selected' : '') : '' }}>
                                {{ $item->work_type_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('work_type')
                        <div class="form-feedback text-danger"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-3">
                    <label for="unit_work" class="col-form-label">कार्य यूनिट</label>
                    <input class="form-control" type="number" id="unit_work" name="unit_work"
                        value="{{ isset($work) ? $work->units_of_work : '' }}">
                </div>
                <div class="col-sm-3">
                    <label for="sanction_amount" class="col-form-label">स्वीकृति राशि (रुपये में)</label>
                    <input class="form-control" type="number" step="0.01" min="0" id="sanction_amount"
                        name="sanction_amount" placeholder="राशि"
                        value="{{ isset($work) ? $work->sanction_amount : '' }}">
                </div>
                <div class="col-sm-3">
                    <label for="latitude" class="col-form-label">अक्षांश (Latitude)</label>
                    <input class="form-control" type="number" step="0.0000001" min="-90" max="90" id="latitude"
                        name="latitude" placeholder="जैसे 23.2156789"
                        value="{{ isset($work) ? $work->latitude : '' }}">
                </div>
                <div class="col-sm-3">
                    <label for="longitude" class="col-form-label">देशान्तर (Longitude)</label>
                    <input class="form-control" type="number" step="0.0000001" min="-180" max="180" id="longitude"
                        name="longitude" placeholder="जैसे 82.8712345"
                        value="{{ isset($work) ? $work->longitude : '' }}">
                    <button type="button" class="btn btn-xs btn-default" style="margin-top:4px" onclick="fillCurrentLocation()">
                        <i class="fa fa-crosshairs"></i> वर्तमान स्थान लें
                    </button>
                    <span id="geo-status" class="small text-muted"></span>
                </div>
                <div class="col-sm-3">
                    <label for="work_name" class="col-form-label">कार्य नाम <span
                            style="color: red">&nbsp;*</span></label>
                    <input class="form-control" type="text" placeholder="कार्य नाम" id="work_name"
                        name="work_name" value="{{ isset($work) ? $work->work_name : '' }}"
                        @error('work_name')
                          form-control-danger
                      @enderror>
                    @error('work_name')
                        <div class="form-feedback text-danger"> {{ $message }}</div>
                    @enderror
                </div>
                @if (is_admin())
                    <div class="col-sm-3">
                        <label for="employeeAdmin" class="col-form-label">नियुक्त कर्मचारी</label>
                        <select name="employeeAdmin" id="employeeAdmin" class="form-control form-select">
                            <option value="">-- कर्मचारी चुनें --</option>
                            @foreach ($Adm_Employee as $list)
                                <option value="{{ $list->emp_id }}"
                                    {{ isset($work) ? ($work->employee_id == $list->emp_id ? 'selected' : '') : '' }}>
                                    {{ $list->emp_name }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                @else
                    <div class="col-sm-3">
                        <label for="employee" class="col-form-label">नियुक्त कर्मचारी</label>
                        <select name="employee" id="employee" class="form-control form-select"
                            @error('employee') form-control-danger
                  @enderror>
                            <option value="">-- कर्मचारी चुनें --</option>
                            @foreach ($Employee as $list)
                                <option value="{{ $list->emp_id }}"
                                    {{ isset($work) ? ($work->employee_id == $list->emp_id ? 'selected' : '') : '' }}>
                                    {{ $list->emp_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee')
                            <div class="form-feedback text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="col-sm-3">
                    <label for="sdo_emp_id" class="col-form-label">नियुक्त एसडीओ</label>
                    <select name="sdo_emp_id" id="sdo_emp_id" class="form-control form-select">
                        <option value="">-- एसडीओ चुनें --</option>
                        @foreach ((is_admin() ? $Adm_Employee : $Employee) as $list)
                            <option value="{{ $list->emp_id }}"
                                {{ isset($work) ? ($work->sdo_emp_id == $list->emp_id ? 'selected' : '') : '' }}>
                                {{ $list->emp_name }}{{ $list->designation?->designation_name ? ' — '.$list->designation->designation_name : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
            {{-- <div class="form-group row">
                <div class="col-sm-4">
                    <div class="form-control checkbox checkbox-success">
                        <input type="checkbox" id="inlineCheckbox2" value="option1" checked="">
                        <label for="inlineCheckbox2"> निविदा के लिए लागू नहीं है </label>
                    </div>
                </div>
                   
            </div> --}}

            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="example-text-input" class="col-sm-12 col-form-label">डी.पी.आर</label>
                </div>
                <div class="col-sm-4">
                    <label for="dpr_startDate" class="col-form-label">अनुमानित प्रारंभ तिथि</label>
                    <input class="form-control" type="date" id="dpr_startDate" name="dpr_startDate"
                        value="{{ isset($work) ? $work->dpr_startDate : '' }}">
                </div>
                <div class="col-sm-4">
                    <label for="dpr_endDate" class="col-form-label">अनुमानित अंतिम तिथि</label>
                    <input class="form-control" type="date" id="dpr_endDate" name="dpr_endDate"
                        value="{{ isset($work) ? $work->dpr_startDate : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="example-text-input" class="col-sm-12 col-form-label">तकनीकी स्वीकृति</label>
                </div>
                <div class="col-sm-4">
                    <label for="ts_startDate" class="col-form-label">अनुमानित प्रारंभ तिथि</label>
                    <input class="form-control" type="date" id="ts_startDate" name="ts_startDate"
                        value="{{ isset($work) ? $work->ts_startDate : '' }}">
                </div>
                <div class="col-sm-4">
                    <label for="ts_endDate" class="col-form-label">अनुमानित अंतिम तिथि</label>
                    <input class="form-control" type="date" id="ts_endDate" name="ts_endDate"
                        value="{{ isset($work) ? $work->ts_endDate : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="example-text-input" class="col-sm-12 col-form-label">प्रशासकीय स्वीकृति</label>
                </div>
                <div class="col-sm-4">
                    <label for="as_startDate" class="col-form-label">अनुमानित प्रारंभ तिथि</label>
                    <input class="form-control" type="date" id="as_startDate" name="as_startDate"
                        value="{{ isset($work) ? $work->as_startDate : '' }}">
                </div>
                <div class="col-sm-4">
                    <label for="as_endDate" class="col-form-label">अनुमानित अंतिम तिथि</label>
                    <input class="form-control" type="date" id="as_endDate" name="as_endDate"
                        value="{{ isset($work) ? $work->as_endDate : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="example-text-input" class="col-sm-12 col-form-label">निविदा है या,</label>
                    <div class="d-flex">
                        <b>निविदा लागू नहीं है</b>
                        <input type="checkbox" value="{{ isset($work) ? $work->tenderChecked : 0 }}" id="NoTender"
                            name="tenderChecked">
                    </div>
                </div>
                <div class="col-sm-4 checkedTender">
                    <label for="tender_startDate" class="col-form-label">अनुमानित प्रारंभ तिथि</label>
                    <input class="form-control" type="date" id="tender_startDate" name="tender_startDate"
                        value="{{ isset($work) ? $work->tender_startDate : '' }}">
                </div>
                <div class="col-sm-4 checkedTender">
                    <label for="tender_endDate" class="col-form-label">अनुमानित अंतिम तिथि</label>
                    <input class="form-control" type="date" id="tender_endDate" name="tender_endDate"
                        value="{{ isset($work) ? $work->tender_endDate : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="example-text-input" class="col-sm-12 col-form-label">कार्य आदेश</label>
                </div>
                <div class="col-sm-4">
                    <label for="workOrder_startDate" class="col-form-label">अनुमानित प्रारंभ तिथि</label>
                    <input class="form-control" type="date" id="workOrder_startDate" name="workOrder_startDate"
                        value="{{ isset($work) ? $work->workOrder_startDate : '' }}">
                </div>
                <div class="col-sm-4">
                    <label for="workOrder_endDate" class="col-form-label">अनुमानित अंतिम तिथि</label>
                    <input class="form-control" type="date" id="workOrder_endDate" name="workOrder_endDate"
                        value="{{ isset($work) ? $work->workOrder_endDate : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="example-text-input" class="col-sm-12 col-form-label">अनुबंध</label>
                </div>
                <div class="col-sm-4">
                    <label for="agreement_startDate" class="col-form-label">अनुमानित प्रारंभ तिथि</label>
                    <input class="form-control" type="date" id="agreement_startDate" name="agreement_startDate"
                        value="{{ isset($work) ? $work->agreement_startDate : '' }}">
                </div>
                <div class="col-sm-4">
                    <label for="agreement_endDate" class="col-form-label">अनुमानित अंतिम तिथि</label>
                    <input class="form-control" type="date" id="agreement_endDate" name="agreement_endDate"
                        value="{{ isset($work) ? $work->agreement_endDate : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="example-text-input" class="col-sm-12 col-form-label">कार्य प्रारम्भ</label>
                </div>
                <div class="col-sm-4">
                    <label for="workStart_startDate" class="col-form-label">अनुमानित प्रारंभ तिथि</label>
                    <input class="form-control" type="date" id="workStart_startDate" name="workStart_startDate"
                        value="{{ isset($work) ? $work->workStart_startDate : '' }}">
                </div>
                {{-- <div class="col-sm-4">
                    <label for="workStart_endDate" class="col-form-label">अनुमानित अंतिम तिथि</label>
                    <input class="form-control" type="date" id="workStart_endDate" name="workStart_endDate"
                        value="{{ isset($work) ? $work->workStart_endDate : '' }}">
                </div> --}}
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="example-text-input" class="col-sm-12 col-form-label">कार्य पूर्ण</label>
                </div>
                <div class="col-sm-4">
                    <label for="workComplete_endDate" class="col-form-label">अनुमानित अंतिम तिथि</label>
                    <input class="form-control" type="date" id="workComplete_endDate" name="workComplete_endDate"
                        value="{{ isset($work) ? $work->workComplete_endDate : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <input type="submit" value="{{ isset($work) ? 'Update' : 'Submit' }}" id="submit"
                        class="btn btn-primary pull-right">
                </div>
            </div>
        </form>
    </div>
</div>
