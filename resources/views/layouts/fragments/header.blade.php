<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="{{$app->make('url')->to('/')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="{{ asset("/favicon.png")}}" alt="KD" width="28px"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img class="user-image" src="{{ asset("/img/logo.png")}}" alt="Dashboard {{ $sebutan_wilayah }}"
                                   width="180px"></span>
    </a>
    <!-- Header Navbar -->
    <?php
    if (Sentinel::guest()){
    ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="{{$app->make('url')->to('login')}}"><img src="{{ asset("/img/login.png")}}" class="user-image" alt="User Image"><span
                                class="hidden-xs">Login</span></a>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar" title="Bantuan!"><i class="fa fa-question-circle fa-lg"></i></a>
                </li>
            </ul>
        </div>
    </nav>
    <?php
    }else{
        $user = Sentinel::getUser();
    ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ asset("/bower_components/admin-lte/dist/img/user2-160x160.jpg") }}"
                             class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ $user->first_name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ asset("/bower_components/admin-lte/dist/img/user2-160x160.jpg") }}"
                                 class="img-circle" alt="User Image">
                            <p>
                                {{ $user->first_name .' '.$user->last_name }}
                                <small>Member since {{ date('M, Y', strtotime($user->created_at)) }}</small>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                {{--<a href="#" class="btn btn-default btn-flat">Profile</a>--}}
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                   onclick="">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar" title="Bantuan!"><i class="fa fa-question-circle fa-lg"></i></a>
                </li>
            </ul>
        </div>
    </nav>
    <?php
    }
    ?>
</header>