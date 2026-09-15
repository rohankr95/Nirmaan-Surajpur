<nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <!-- Sidebar toggle button-->
        <span class="sr-only">Toggle navigation</span>
        <span class="pe-7s-menu"></span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

{{--            <li class="dropdown notifications-menu">--}}
{{--                <a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
{{--                    <i class="pe-7s-date"></i>--}}
{{--                    <span class="label label-warning">8</span>--}}
{{--                </a>--}}
{{--                <ul class="dropdown-menu">--}}
{{--                    <li class="header">Financial Year</li>--}}
{{--                    <li>--}}
{{--                        <ul class="menu">--}}
{{--                            <li><a href="#"><i class="ti-calendar color-gray"></i> Year 2023-24 </a></li>--}}
{{--                            <li><a href="#"><i class="ti-calendar color-gray"></i> Year 2022-23 </a></li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li class="footer"><a href="#">ADD NEW</a></li>--}}
{{--                </ul>--}}
{{--            </li>--}}

{{--            <!-- settings -->--}}
{{--            <li class="dropdown dropdown-user">--}}
{{--                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"></i></a>--}}
{{--                <ul class="dropdown-menu">--}}
{{--                    <li><a href="{{ route('profile') }}"><i class="pe-7s-users"></i> User Profile</a></li>--}}
{{--                    <li><a href="{{ route('logout') }}"><i class="pe-7s-key"></i> Logout</a></li>--}}
{{--                </ul>--}}
{{--            </li>--}}

{{--            <li class="dropdown dropdown-user">--}}
{{--                <a href="{{ route('profile') }}" > <i class="fa fa-user-circle text-success"></i></a>--}}
{{--            </li>--}}

            <li class="dropdown dropdown-user">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-user-circle text-success"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('profile') }}"><i class="fa fa-edit"></i>प्रोफाइल बदलें</a></li>
                    <li><a href="{{ route('change-password') }}"><i class="fa fa-key mr-2"></i>पासवर्ड बदलें</a></li>
                </ul>
            </li>

            <li class="dropdown dropdown-user">
                <a href="{{ route('logout') }}" > <i class="fa fa-power-off text-danger"></i></a>
            </li>
        </ul>
    </div>
</nav>
