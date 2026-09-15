<aside class="main-sidebar">
    <!-- sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel text-center">
            <div class="image">
                <img src="{{asset('assets/dist/img/avatar.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="info">
                <p>{{ session()->get('name') }} </p>
                <small class="text-warning">{{ session()->get('office') }}</small>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form> <!-- /.search form -->
        <!-- sidebar menu -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="{{echo_active(request()->route()->getName()=='dashboard')}}">
                <a href="{{ route('dashboard') }}"><i class="ti-home"></i> <span>डैशबोर्ड </span></a>
            </li>

            <li class="{{echo_active(request()->route()->getName()=='work.index')}}">
                <a href="{{ route('work.index') }}"><i class="ti-ruler-pencil"></i> <span>कार्य</span></a>
            </li>
            <li class="{{echo_active(request()->route()->getName()=='technical-sanction.index')}}">
                <a href="{{ route('technical-sanction.index') }}"><i class="ti-home"></i> <span>तकनीकी स्वीकृति</span></a>
            </li>
            <li class="{{echo_active(request()->route()->getName()=='administrative-sanction.index')}}">
                <a href="{{ route('administrative-sanction.index') }}"><i class="ti-home"></i> <span>प्रशासकीय  स्वीकृति</span></a>
            </li>
            <li class="{{echo_active(request()->route()->getName()=='tender.index')}}">
                <a href="{{ route('tender.index') }}"><i class="ti-home"></i> <span>निविदा</span></a>
            </li>
            <li class="{{echo_active(request()->route()->getName()=='work-progress.index')}}">
                <a href="{{ route('work-progress.index') }}"><i class="ti-bar-chart"></i> <span>कार्य प्रगति </span></a>
            </li>
            <li class="treeview {{echo_active(request()->segment(1)=="reports")}}">
                <a href="#">
                    <i class="ti-files"></i> <span>रिपोर्ट</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
{{--                    <li class="{{ echo_active(request()->route()->getName()=="reports.works") }}"><a href="{{ route("reports.works") }}">कार्य रिपोर्ट</a></li>--}}
{{--                    <li class="{{ echo_active(request()->route()->getName()=="reports.technical-sanction") }}"><a href="{{ route("reports.technical-sanction") }}">तकनीकी स्वीकृति रिपोर्ट</a></li>--}}
{{--                    <li class="{{ echo_active(request()->route()->getName()=="reports.administrative-sanction") }}"><a href="{{ route("reports.administrative-sanction") }}">प्रशासन स्वीकृति रिपोर्ट</a></li>--}}
                    <li class="{{ echo_active(request()->route()->getName()=="reports.block-wise") }}"><a href="{{ route("reports.block-wise") }}">ग्रामीण रिपोर्ट</a></li>
                    <li class="{{ echo_active(request()->route()->getName()=="reports.city-wise") }}"><a href="{{ route("reports.city-wise") }}">नगरीय निकाय रिपोर्ट</a></li>
                    <li class="{{ echo_active(request()->route()->getName()=="reports.agency-wise") }}"><a href="{{ route("reports.agency-wise") }}">एजेंसीवार रिपोर्ट</a></li>
                    <li class="{{ echo_active(request()->route()->getName()=="reports.employee-agency-wise") }}"><a href="{{ route("reports.employee-agency-wise") }}">एजेंसीवार कर्मचारी रिपोर्ट</a></li>
                    <li class="{{ echo_active(request()->route()->getName()=="reports.counts-uploaded-docs") }}"><a href="{{ route("reports.counts-uploaded-docs") }}">एजेंसीवार दस्तावेज़ों की संख्या रिपोर्ट  </a></li>
                    <li class="{{ echo_active(request()->route()->getName()=="reports.agency-wise-30-days-pending") }}"><a href="{{ route("reports.agency-wise-30-days-pending") }}">एजेंसीवार 30 दिन-लंबित रिपोर्ट </a></li>
                    <li class="{{ echo_active(request()->route()->getName()=="reports.scheme-wise") }}"><a href="{{ route("reports.scheme-wise") }}">योजनावार रिपोर्ट</a></li>
                    @if(is_admin())
                    <li class="{{ echo_active(request()->route()->getName()=="reports.logs-list") }}"><a href="{{ route("reports.logs-list") }}">लॉगिन स्थिति रिपोर्ट</a></li>
                    <li class="{{ echo_active(request()->route()->getName()=="reports.last-status") }}"><a href="{{ route("reports.last-status") }}">कार्य की अंतिम स्थिति रिपोर्ट</a></li>
                    @endif
                </ul>   
            </li>

            <li class="treeview {{echo_active(request()->segment(1)=="master")}}">
                <a href="#">
                    <i class="ti-settings"></i> <span>मास्टर डाटा</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ echo_active((request()->route()->getName()=="parliamentary_constituency.index")
                                    || (request()->route()->getName()=="assembly_constituency.index")
                                    || (request()->route()->getName()=="states.index")
                                    || (request()->route()->getName()=="district.index")
                                    || (request()->route()->getName()=="subdivisions.index")
                                    || (request()->route()->getName()=="blocks.index")
                                    || (request()->route()->getName()=="villages.index")
                                    || (request()->route()->getName()=="cities.index")
                                    || (request()->route()->getName()=="wards.index")
                        ) }}">
                        <a href="#">लोकेशन मास्टर<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                        <ul class="treeview-menu">
                            <li class="{{ echo_active(request()->route()->getName()=="parliamentary_constituency.index") }}"><a href="{{ route("parliamentary_constituency.index") }}">संसदीय क्षेत्र</a></li>
                            <li class="{{ echo_active(request()->route()->getName()=="assembly_constituency.index") }}"><a href="{{ route("assembly_constituency.index") }}">विधानसभा क्षेत्र</a></li>
                            <li class="{{ echo_active(request()->route()->getName()=="states.index") }}"><a href="{{ route("states.index") }}">राज्य</a></li>
                            <li class="{{ echo_active(request()->route()->getName()=="district.index") }}"><a href="{{ route("district.index") }}">ज़िला</a></li>
                            <li class="{{ echo_active(request()->route()->getName()=="subdivisions.index") }}"><a href="{{ route("subdivisions.index") }}">अनुभाग</a></li>
                            <li class="{{ echo_active(request()->route()->getName()=="blocks.index") }}"><a href="{{ route("blocks.index") }}">विकासखंड</a></li>
                            <li class="{{ echo_active(request()->route()->getName()=="villages.index") }}"><a href="{{ route("villages.index") }}">ग्राम पंचायत</a></li>
                            <li class="{{ echo_active(request()->route()->getName()=="cities.index") }}"><a href="{{ route("cities.index") }}">शहर</a></li>
                            <li class="{{ echo_active(request()->route()->getName()=="wards.index") }}"><a href="{{ route("wards.index") }}">वार्ड</a></li>
                        </ul>
                    </li>
                    <li class="{{ echo_active(request()->route()->getName()=="schemes.index") }}"><a href="{{ route("schemes.index") }}">योजनाएं</a></li>

                    @if(is_admin())
                    <li class="{{ echo_active(request()->route()->getName()=="departments.index") }}"><a href="{{ route("departments.index") }}">विभाग</a></li>
                    @endif

                    @if(is_admin())
                    <li class="{{ echo_active(request()->route()->getName()=="offices.index") }}"><a href="{{ route("offices.index") }}">कार्यालय</a></li>
                    @endif

                    <li class="{{ echo_active(request()->route()->getName()=="work_types.index") }}"><a href="{{ route("work_types.index") }}">कार्य प्रकार और चरण</a></li>

                    <li class="{{ echo_active(request()->route()->getName()=="employee.index") }}"><a href="{{ route("employee.index") }}">कर्मचारी</a></li>
                    
                    @if (is_admin())
                    <li class="{{ echo_active(request()->route()->getName()=="employee-designation.index") }}"><a href="{{ route("employee-designation.index") }}">कर्मचारी पदनाम</a></li>
                    @endif
                    
                    @if(is_admin())
                    <li class="{{ echo_active(request()->route()->getName()=="users.index") }}"><a href="{{ route("users.index") }}">उपयोगकर्ता</a></li>
                    @endif
                </ul>
            </li>
            <li class="header"></li>
        </ul>
    </div> <!-- /.sidebar -->
</aside>
