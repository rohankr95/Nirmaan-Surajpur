<!DOCTYPE html>
<html lang="en">
<head>
        @include('layout.includes.head')
        @yield('css')
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <a href="{{ route('index') }}" class="logo"> <!-- Logo -->
                    <span class="logo-mini">
                        <img src="{{asset('assets/dist/img/mini-logo.png')}}" alt="">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('assets/dist/img/nirmaan.png')}}" alt="">
                    </span>
                </a>
                @include('layout.includes.header_nav')
            </header>
            <!-- =============================================== -->
            <!-- Left side column. contains the sidebar -->
            @include('layout.includes.sidebar')
            <!-- =============================================== -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @yield('content')
            </div>
            @include('layout.includes.footer')
        </div>
        @include('layout.includes.script')
        @yield('script')
    </body>

</html>
