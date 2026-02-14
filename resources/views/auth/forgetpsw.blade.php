<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
 
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Forgot Password | Panel</title>
    <link rel="apple-touch-icon" href="{{url('app-assets/images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/vendors/css/forms/icheck/custom.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/bootstrap-extended.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/colors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/components.min.css')}}">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/core/menu/menu-types/vertical-menu-modern.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/core/colors/palette-gradient.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('app-assets/css/pages/login-register.min.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/style.css')}}">
    <!-- END: Custom CSS-->

  </head>
  <!-- END: Head-->
  <style>
    img{
        weight:80px;
        height:80px;
    }
</style>
  <!-- BEGIN: Body-->
  <body class="vertical-layout vertical-menu-modern 1-column   blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body"><section class="row flexbox-container">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
            <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
                <div class="card-header border-0 pb-0">
                    <!-- <div class="card-title text-center">
                        <img src="../../../app-assets/images/logo/stack-logo-dark.png" alt="branding logo">
                    </div> -->
                    <b><h5 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2"><span>
                        We will send you a link to reset password.</span></h5></b>
                </div>
                <div class="card-content">
                    <div class="card-body">
                    @include('notifications')
                        <form class="form-horizontal"action="{{ route('forget.password.post') }}" method="post" >
                         @csrf
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" name="email" class="form-control form-control-lg" id="user-email"
                                    placeholder="Your Email Address" required>
                                <div class="form-control-position">
                                    <i class="feather icon-mail"></i>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </fieldset>
                            <button type="submit" class="btn btn-outline-primary btn-lg btn-block"><i
                                    class="feather icon-unlock"></i> Recover Password</button>
                        </form>
                    </div>
                </div>
                <!-- <div class="card-footer border-0">
                    <p class="float-sm-left text-center"><a href="{{url('login')}}" class="card-link">Login</a></p>
                    <p class="float-sm-right text-center">New to Stack ? <a href="register-simple.html"
                            class="card-link">Create Account</a></p>
                </div> -->
            </div>
        </div>
    </div>
   </section>
        </div>
      </div>
    </div>
    <!-- END: Content-->

<!-- BEGIN: Vendor JS-->
<script src="{{url('app-assets/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{url('app-assets/vendors/js/forms/icheck/icheck.min.js')}}"></script>
    <script src="{{url('app-assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{url('app-assets/js/core/app-menu.min.js')}}"></script>
    <script src="{{url('app-assets/js/core/app.min.js')}}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{url('app-assets/js/scripts/forms/form-login-register.min.js')}}"></script>
    <!-- END: Page JS-->

  </body>
  <!-- END: Body-->


    

</html>