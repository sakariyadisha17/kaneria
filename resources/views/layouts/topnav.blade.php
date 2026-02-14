<!-- BEGIN: Header-->
<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-lg-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="feather icon-menu font-large-1"></i></a></li>
                <li class="nav-item mr-auto">
                    <a class="navbar-brand" href="#">
                        <img class="brand-logo" alt="stack admin logo" src="{{url('/app-assets/images/logo/stack-logo-light.png')}}">
                        @hasrole('Admin')
                        <h4 class="brand-text">Admin</h4> 
                        @endrole
                        @hasrole('Doctor')
                        <h4 class="brand-text">Doctor</h4> 
                        @endrole
                        @hasrole('Medical Officer')
                        <h4 class="brand-text">MOfficer</h4> 
                        @endrole
                        @hasrole('Nursing')
                        <h4 class="brand-text">Nursing</h4> 
                        @endrole
                        @hasrole('Receptionist')
                        <h4 class="brand-text">Receptionist</h4> 
                        @endrole
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon feather icon-toggle-right font-medium-3 white" data-ticon="feather.icon-toggle-right"></i></a></li>
                <li class="nav-item d-lg-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a></li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon feather icon-maximize"></i></a></li>
                    <li class="nav-item nav-search"><a class="nav-link nav-link-search" href="#"><i class="ficon feather icon-search"></i></a>
                        <div class="search-input">
                        <input class="input" type="text" placeholder="Explore Stack..." tabindex="0" data-search="template-search">
                        <div class="search-input-close"><i class="feather icon-x"></i></div>
                        <ul class="search-list"></ul>
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <div class="avatar avatar-online"><img src="{{url('hospital_logo.jpg')}}" alt="avatar"><i></i></div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ URL('profile') }}"><i class="feather icon-user"></i> Edit Profile</a>
                            <!-- <div class="dropdown-divider"></div> -->
                            <a class="dropdown-item" href="{{ URL('logout') }}"><i class="feather icon-power"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- END: Header-->
