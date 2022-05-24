<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="{{$app->make('url')->to('/')}}" target="_blank" class="logo">
        <span class="logo-mini"><b>DK</b></span>
        <span class="logo-lg"><b>OpenDK</b></span>
    </a>
    <!-- Header Navbar -->
    @if (Auth::guest())
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
    @else
    @php $user = auth()->user(); @endphp
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
                        <img src="{{ avatar($user->image) }}" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ $user->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ avatar($user->image) }}" class="img-circle" alt="User Image">
                            <p>
                                {{ $user->name }}
                                <small>Member since {{ date('M, Y', strtotime($user->created_at)) }}</small>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                {{--<a href="#" class="btn btn-default btn-flat">Profile</a>--}}
                            </div>
                            <div class="pull-right">
                               

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"  >
                                    {{ csrf_field() }}
                                    <button class="btn btn-default btn-flat" type="submit">
                                        Logout
                                    </button>
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
    @endif
</header>