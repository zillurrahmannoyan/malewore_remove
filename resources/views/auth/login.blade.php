 <!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Login - {{ App\Setting::getInfo('site_name') }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ asset('assets/global/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{ asset('assets/pages/css/login-4.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <style>
        .content {
            width: 100%!important;
        }
        .text-danger {
            margin: 0;
            color: #e73d4a!important;
            font-weight:bold;
        }
    </style>

    <body class="login">

 <!-- BEGIN LOGO -->

        <div class="container">
                <div class="col-md-12">
                    <div class="logo">
                        <a href="/">
                            <img src="{{ asset('uploads/images/logo_small.png') }}" alt="" width="100" /> 
                        </a>
                    </div>  
                </div>
        </div>



        <div class="col-md-4"></div>
        <div class="col-md-4">            

        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form" action="" method="post">

                {!! csrf_field() !!}
                <input type="hidden" name="op" value="1">

                <h3 class="form-title">Login to your account</h3>
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span> Enter any username and password. </span>
                </div>


                @if ($message = Session::get('success'))
                <div class="alert alert-success" style="margin-bottom:10px;">
                    {{ $message }}
                </div>
                @endif

                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissable" style="margin-bottom:10px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã</button>
                    {{ $message }}
                </div>
                <!-- <p class="bg-danger">
                    <h4>Error</h4>
                    {{ $message }}
                </p> -->
                @endif

                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Username</label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username" /> </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label">                       
                    <input type="checkbox" name="remember" class="form-control" value="1">Remember me</label> 
                </div>
                

                <div class="form-actions">
                    <button type="submit" class="btn blue btn-block">Login <i class="m-icon-swapright m-icon-white"></i>
                </div>
            </form>
            <!-- END LOGIN FORM -->

         
        </div>
        <!-- END LOGIN -->


        </div>

        <div class="col-md-6" style="display:none;">
            
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form action="" method="post">
                {!! csrf_field() !!}

                <h3>Request an SDS </h3>

                @if ($message = Session::get('req-success'))
                <div class="alert alert-success" style="margin-bottom:10px;">
                    {{ $message }}
                </div>
                @endif

                @if ($message = Session::get('req-error'))
                <div class="alert alert-danger alert-dismissable" style="margin-bottom:10px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã</button>
                    {{ $message }}
                </div>
                <!-- <p class="bg-danger">
                    <h4>Error</h4>
                    {{ $message }}
                </p> -->
                @endif

                <p>
                Are you looking for an SDS not listed?<br>
                Please use this request form and our team will notify you upon completion.
                </p>
                <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ Input::old('name') }}">
                {!! $errors->first('name','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                </div>
                <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ Input::old('email') }}">
                {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                </div>
                <div class="form-group">
                <textarea name="message" class="form-control" rows="5" placeholder="Desired SDS">{{ Input::old('message') }}</textarea>
                {!! $errors->first('message','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                </div>

                 <div class="form-actions">
                    <input type="hidden" name="request" value="1">
                    <button type="submit" class="btn blue">Submit <i class="m-icon-swapright m-icon-white"></i>
                </div>

            </form>
</div>

        <!-- BEGIN COPYRIGHT -->
        <div class="copyright">{{ date('Y') }} Â© {{ App\Setting::getInfo('copy_right') }}</div>
        <!-- END COPYRIGHT -->    

        </div>


              <!--[if lt IE 9]>
<script src="{{ asset('assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/excanvas.min.js') }}"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/backstretch/jquery.backstretch.min.js') }}" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{ asset('assets/pages/scripts/login-4.min.js') }}" type="text/javascript"></script>


    </body>

</html>