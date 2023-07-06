<!DOCTYPE html>
<html lang="en" class="h-100">



<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta name="description" content="Urrbor Admin">
    <title>Bdapps Content </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('image')}}/logo2.jpg?{{time()}}">
    <link rel="stylesheet" href="{{asset('admin')}}/css/style.css?{{time()}}">

</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
    @if(Session::has('error'))
    <div class="col-md-10 col-sm-10 col-10 offset-md-1 offset-sm-10 alert alert-danger">
        {{Session::get('error')}}
    </div>
    @endif

                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Sign in your account</h4>
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label><strong>Email</strong></label>
                                            <input type="email" class="form-control"  name="email">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Password</strong></label>
                                            <input type="password" class="form-control"  name="password">
                                        </div>
                                        <div class="form-row d-flex justify-content-between mt-4 mb-2">


                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Sign me in</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{asset('assets')}}/admin/vendor/global/global.min.js"></script>
	<script src="{{asset('assets')}}/admin/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="{{asset('assets')}}/admin/js/custom.min.js"></script>
    <script src="{{asset('assets')}}/admin/js/dlabnav-init.js"></script>

</body>


<!-- Mirrored from edumin.dexignlab.com/xhtml/page-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 20 Dec 2020 15:51:24 GMT -->
</html>
