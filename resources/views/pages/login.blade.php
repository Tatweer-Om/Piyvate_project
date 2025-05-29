<!DOCTYPE html>
<html lang="en" class="h-100">

<!-- Mirrored from eres.dexignzone.com/xhtml/page-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 17 Feb 2025 06:18:51 GMT -->
<head>

	<!-- Title -->
	<title>Login to Piyavte</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="robots" content="">


	<!-- Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Favicon icon -->
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
    <link href="{{ asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link class="main-css" href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{  asset('vendor/toastr/css/toastr.min.css')}}">

<style>
    .small-alert {
    font-size: 14px;
    padding: 8px 12px;
    background-color: #f44336; /* Red */
    color: #fff;
    border-radius: 4px;
    width: fit-content;
    margin: 10px auto;
}
</style>


</head>


@if(session('error'))
    <div class="alert alert-danger small-alert" id="errorAlert">
        {{ session('error') }}
    </div>
@endif

<script>
    setTimeout(function() {
        let alertBox = document.getElementById('errorAlert');
        if (alertBox) {
            alertBox.style.transition = 'opacity 0.5s ease';
            alertBox.style.opacity = '0';
            setTimeout(function() {
                alertBox.style.display = 'none';
            }, 500);
        }
    }, 3000); // 3 seconds
</script>


<body class="vh-100">

	<div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
									<div class="text-center mb-3">
										<a><img src="{{ asset('images/logo/piyalogo-1.png')}}" alt=""></a>
									</div>
                                    <h4 class="text-center mb-4">Sign in your account</h4>
                                    <form class="login_form">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-label">User Name Or Email</label>
                                            <input type="text" class="form-control user_name" name="user_name">
                                        </div>
										<label class="form-label">Password</label>
                                        <div class="mb-3 position-relative">
											<input type="password" id="dz-password" name="password" class="form-control password" >
											<span class="show-pass eye">
												<i class="fa fa-eye-slash"></i>
												<i class="fa fa-eye"></i>
											</span>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Sign Me In</button>
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



    @include('custom_js.custom_js')

    @php

    $routeName = Route::currentRouteName();
    $segments = explode('.', $routeName);
    $route_name = isset($segments[0]) ? $segments[0] : null;

@endphp

    @if ($route_name == 'login_page')
        @include('custom_js.login_js')
        @elseif ($route_name == 'home')
        @include('custom_js.login_js')
    @endif

    <!-- Required vendors -->
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <script src="{{ asset('js/deznav-init.js') }}"></script>
    <script src="{{ asset('js/demo.js') }}"></script>
    <script src="{{ asset('js/styleSwitcher.js') }}"></script>
    <script src="{{ asset('vendor/toastr/js/toastr.min.js')}}"></script>
    <script src="{{ asset('js/plugins-init/toastr-init.js')}}"></script>


</body>

<!-- Mirrored from eres.dexignzone.com/xhtml/page-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 17 Feb 2025 06:18:51 GMT -->
</html>
