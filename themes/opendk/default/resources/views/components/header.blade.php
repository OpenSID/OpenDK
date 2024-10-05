<header id="navbar" class="main-header">
    <nav class="navbar  navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a href="{{ route('beranda') }}" class="navbar-brand">
                    <img class="logo-kab" src="{{ is_logo($profil->file_logo) }}" alt="OpenDK" id="logo-brand">
                    <small class="text-kab">{{ strtoupper('PEMERINTAH KAB. ' . $profil->nama_kabupaten) }}</small>
                    <small class="text-kec">{{ strtoupper($sebutan_wilayah . ' ' . $profil->nama_kecamatan) }}</small>
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    @foreach ($navmenus as $nav)
                        @if ($nav['children']->count() > 0)
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> {{ $nav['name'] }} <span class="caret"></span></a>
                                <ul class="dropdown-menu fadeIn animated" role="menu">
                                    @foreach ($nav['children'] as $child)
                                        <li><a href="{{ url($child['url']) }}" target="{{ $child['target'] }}">{{ $child['name'] }}</a></li>                                   
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li><a href="{{ url($nav['url']) }}" target="{{ $nav['target'] }}">{{ $nav['name'] }}<span class="sr-only">(current)</span></a></li>
                        @endif
                    @endforeach
                    {{-- @foreach ($navigations as $nav)
                        @if ($nav['childrens']->count() > 0)
                            <li class="dropdown">
                                <a href="{{ $nav['full_url'] }}" class="dropdown-toggle" data-toggle="dropdown"> {{ $nav['name'] }} <span class="caret"></span></a>
                                <ul class="dropdown-menu fadeIn animated" role="menu">
                                    @foreach ($nav['childrens'] as $child)
                                        <li><a href="{{ $child['full_url'] }}">{{ $child['name'] }}</a></li>                                   
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li><a href="{{ url($nav['full_url']) }}">{{ $nav['name'] }}<span class="sr-only">(current)</span></a></li>
                        @endif
                    @endforeach --}}

                    @if (auth()->guest())
                        <li><a href="{{ route('login') }}">LogIn<span class="sr-only">(current)</span></a></li>
                    @else
                        <li><a href="{{ route('dashboard') }}">Admin</a></li>
                        <li><a id="loggout" href="{{ route('logout') }}">LogOut<span class="sr-only">(current)</span></a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    @endif
                </ul>
            </div>
    </nav>
</header>

<link href="https://unpkg.com/minibarjs@latest/dist/minibar.min.css" rel="stylesheet" type="text/css">
<script src="https://unpkg.com/minibarjs@latest/dist/minibar.min.js" type="text/javascript"></script>
