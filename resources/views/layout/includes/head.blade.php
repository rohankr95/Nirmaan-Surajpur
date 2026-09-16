<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title','WORKS - Work Monitoring System')</title>

<!-- Favicon and touch icons -->
<link rel="shortcut icon" href="{{asset('assets/dist/img/ico/favicon.png')}}" type="image/x-icon">
{{--<link rel="apple-touch-icon" type="image/x-icon" href="{{asset('assets/dist/img/ico/apple-touch-icon-57-precomposed.png')}}">--}}
{{--<link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{asset('assets/dist/img/ico/apple-touch-icon-72-precomposed.png')}}">--}}
{{--<link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{asset('assets/dist/img/ico/apple-touch-icon-114-precomposed.png')}}">--}}
{{--<link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{asset('assets/dist/img/ico/apple-touch-icon-144-precomposed.png')}}">--}}


<!-- jquery-ui css -->
<link href="{{asset('assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" type="text/css"/>
<!-- Bootstrap -->
<link href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
<!-- Bootstrap rtl -->
<!--<link href="{{asset('assets/bootstrap-rtl/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css"/>-->
<!-- Lobipanel css -->
<link href="{{asset('assets/plugins/lobipanel/lobipanel.min.css')}}" rel="stylesheet" type="text/css"/>
<!-- Pace css -->
<link href="{{asset('assets/plugins/pace/flash.css')}}" rel="stylesheet" type="text/css"/>
<!-- Font Awesome -->
<link href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
<!-- Pe-icon -->
<link href="{{asset('assets/pe-icon-7-stroke/css/pe-icon-7-stroke.css')}}" rel="stylesheet" type="text/css"/>
<!-- Themify icons -->
<link href="{{asset('assets/themify-icons/themify-icons.css')}}" rel="stylesheet" type="text/css"/>
<!-- Toastr css -->
<link href="{{asset('assets/plugins/toastr/toastr.css')}}" rel="stylesheet" type="text/css"/>
<!-- dataTables css -->
<link href="{{ asset('assets/plugins/datatables/dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
<!-- Select2 CSS -->
<link href="{{ asset('assets/vendor/select2/select2.min.css') }}" rel="stylesheet" />
<!-- Theme style -->
<link href="{{asset('assets/dist/css/styleBD.css')}}" rel="stylesheet" type="text/css"/>

<style>

    input[class^='form-control'] {
        /*height: 30px !important;*/
    }
    [class^='select2'] {
        border-radius: 0 !important;
        border-color: #dddddd !important;
    }
    .font-weight-bold{
        font-weight: bold;
    }
    .fixed-panel-body{
        min-height: 250px;
        max-height: 250px;
        overflow-y: scroll  ;
    }
    .fixed-panel-body table thead{
        position: sticky;
        top: 0;
    }
    td.count{
        font-weight: bold;
        font-size: 16px;

    }
    td.count a{
        text-decoration: underline;
    }

    #loader {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100px; /* Adjust the width as needed */
    height: 100px; /* Adjust the height as needed */
    z-index: 1000; /* Adjust the z-index as needed to ensure it appears above other elements */
}
</style>
