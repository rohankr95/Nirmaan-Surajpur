<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from 74.220.209.110/template/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 21 Mar 2023 09:34:00 GMT -->
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>निर्माण - लॉग इन</title>

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/dist/img/ico/favicon.png" type="image/x-icon">
        <link rel="apple-touch-icon" type="image/x-icon" href="assets/dist/img/ico/apple-touch-icon-57-precomposed.png">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="assets/dist/img/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="assets/dist/img/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="assets/dist/img/ico/apple-touch-icon-144-precomposed.png">

        <!-- Bootstrap -->
        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <!-- Bootstrap rtl -->
        <!--<link href="assets/bootstrap-rtl/bootstrap-rtl.min.css" rel="stylesheet" type="text/css"/>-->
        <!-- Pe-icon-7-stroke -->
        <link href="assets/pe-icon-7-stroke/css/pe-icon-7-stroke.css" rel="stylesheet" type="text/css"/>
        <!-- style css -->
        <link href="assets/dist/css/styleBD.css" rel="stylesheet" type="text/css"/>
        <!-- Theme style rtl -->
        <!--<link href="assets/dist/css/styleBD-rtl.css" rel="stylesheet" type="text/css"/>-->
    </head>
    <body style="background: url('{{ asset('images/res/auth_bg.jpg') }}'); background-repeat: no-repeat; background-size: cover;">
        <!-- Content Wrapper -->
        <div class="login-wrapper">
{{--            <div class="back-link">--}}
{{--                <a href="index.html" class="btn btn-success">Back to Dashboard</a>--}}
{{--            </div>--}}
            <div class="container-center">
                <div class="panel panel-bd">
                    <div class="panel-heading">
                        <div class="view-header">
                            <div class="header-icon">
                                <i class="pe-7s-unlock"></i>
                            </div>
                            <div class="header-title">
                                <h3>निर्माण - लॉगिन</h3>
                                <small><strong>कृपया अपना लॉगिन आईडी और पासवर्ड दर्ज करें.</strong></small>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('authenticate')  }}" method="POST">
                            @csrf
                            <div class="form-group @error('login_id') has-error @enderror">
                                <label class="control-label" for="login_id">लॉगिन आईडी</label>
                                <input type="text" title="अपना लॉगिन आईडी दर्ज करें" placeholder="आईडी" value="{{ old('login_id') }}" name="login_id" id="username" class="form-control @error('login_id') form-control-danger @enderror">
                                @error('login_id') <div class="form-feedback text-danger"> {{ $message }}</div> @enderror
                            </div>
                            <div class="form-group @error('password') has-error @enderror">
                                <label class="control-label" for="password">पासवर्ड</label>
                                <input type="password" title="अपना पासवर्ड दर्ज करें" placeholder="******" value="" name="password" id="password" class="form-control @error('password') form-control-danger @enderror">
                                @error('password') <div class="form-feedback text-danger"> {{ $message }}</div> @enderror
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">लॉगिन करे</button>
                            </div>
                        </form>
                    </div>
                </div>
                @if(session()->has('error'))

                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h3>Message</h3>
                        </div>
                    </div>
                    <div class="panel-body">
                        {{ session()->get('error')  }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        <!-- /.content-wrapper -->
        <!-- jQuery -->
        <script src="assets/plugins/jQuery/jquery-1.12.4.min.js" type="text/javascript"></script>
        <!-- bootstrap js -->
        <script src="assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    </body>

<!-- Mirrored from 74.220.209.110/template/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 21 Mar 2023 09:34:00 GMT -->
</html>
