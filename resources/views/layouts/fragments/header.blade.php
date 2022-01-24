<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="{{$app->make('url')->to('/')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="{{ is_logo($profil->file_logo) }}" alt="OpenDK" width="42px"></span>
        <!-- logo for regular state and mobile devices -->
        <div class="logo-lg" style="justify-content: flex-start; height: 100%; width:100%; display: flex;">
            <div><img class="user-image" src="{{ is_logo($profil->file_logo) }}" style="padding-right:5px; max-width:42px" alt="KD" width="42px"></div>
            <div style="text-align: left; line-height: 20px; margin-bottom: auto; margin-top: auto;">
                @if(count(explode(' ', $profil->nama_kabupaten)) > 15)
                <div class="" style="font-size:11px;">{{ strtoupper('Pemerintah Kab. ' . $profil->nama_kabupaten) }}</div>
                @else
                <div class="" style="font-size:9px;">{{ strtoupper('Kab. ' . $profil->nama_kabupaten) }}</div>
                @endif
                <div class="" style="font-size: clamp(10px, 2vw, 10px); font-weight:600; ">{{ strtoupper($sebutan_wilayah.' '.$profil->nama_kecamatan) }}</div>
            </div>
        </div>
    </a>
    <!-- Header Navbar -->
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
                        <img src="{{ asset('/bower_components/admin-lte/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ Str::limit(Sentinel::getUser()->name, 20) }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ asset('/bower_components/admin-lte/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                            <p>
                            {{ Str::limit(Sentinel::getUser()->name, 20) }}
                                <small>Member since {{ date('M, Y', strtotime(Sentinel::getUser()->created_at)) }}</small>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
</header>
