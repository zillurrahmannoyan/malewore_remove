 <div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="">
                <img src="{{ asset('uploads/images/logo_small.png') }}" alt="PabilePo.ph" class="logo-default" width="20" /> </a>
            
            @if(Auth::user()->role != 'user')
            <div class="menu-toggler sidebar-toggler"></div>
            @endif

        </div>
        <!-- END LOGO -->
        
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->

        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">

                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" class="img-circle" src="{{ @hasPhoto(Auth::user()->photo) }}"/>
                        <span class="username username-hide-on-mobile"> {{ @ucwords(Auth::user()->fname) }} </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        @if(Auth::user()->role != 'user')
                        <li>
                            <a href="{{ URL::Route('backend.users.profile') }}">
                                <i class="icon-user"></i> My Profile </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ URL::Route('backend.auth.logout') }}">
                                <i class="icon-key"></i> Log Out </a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->

            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>