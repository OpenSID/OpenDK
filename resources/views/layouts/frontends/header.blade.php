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
                    @foreach ($navigations as $nav)
                        @if (!empty($nav['childs']))
                            @if ($nav['slug'] === 'profil')
                                <li class="dropdown @if (Request::is($nav['slug'] . '/*')) active @endif menu-large">
                                    <a href="{{ $nav['url'] }}" class="dropdown-toggle" data-toggle="dropdown"> {{ strtoupper($nav['name']) }} <span class="caret"></span></a>
                                    <div class="dropdown-menu  fadeIn animated">
                                        <div class="container">
                                            <ul class="row megamenu">
                                                <li class="col-md-4 col-sm-2">
                                                    <ul class="mega-list">
                                                        @foreach ($nav['childs'] as $child)
                                                            <li class="@if (Request::is($child['url'])) active @endif"><a class="text-bold" href="{{ $child['url'] }}"><i class="fa fa-arrow-circle-right text-blue"></i> {{ $child['name'] }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                                <li class="col-md-4 col-sm-4">
                                                    @if ($profil->sambutan)
                                                        <h4 class="text-bold text-center">Sambutan {{ $sebutan_kepala_wilayah }} {{ $profil->nama_kecamatan }}</h4>
                                                        <small class="" style="text-align:justify;">{{ strip_tags($profil->sambutan) }} </small>
                                                    @endif
                                                </li>
                                                <li class="col-md-4 col-sm-3 text-center">
                                                    @if ($camat)
                                                        <img src="@if (isset($camat->foto)) {{ asset($camat->foto) }} @else {{ asset('img/no-profile.png') }} @endif" width="200px" class="img-user">
                                                        <h6 class="text-bold no-padding">{{ $camat->namaGelar }}</h6>
                                                        <h6>{{ $sebutan_kepala_wilayah }} {{ $profil->nama_kecamatan }} </h6>
                                                    @endif
                                                </li>
                                                <li style="margin-left:-50px" class="col-sm-3"></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @else
                                <li class="dropdown @if (Request::is($nav['slug'] . '/*')) active @endif">
                                    <a href="{{ $nav['url'] }}" class="dropdown-toggle" data-toggle="dropdown"> {{ strtoupper($nav['name']) }} <span class="caret"></span></a>
                                    <ul class="dropdown-menu fadeIn animated" role="menu">
                                        @foreach ($nav['childs'] as $child)
                                            <li><a href="{{ $child['url'] }}">{{ $child['name'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @else
                            @if ($nav['slug'] === 'desa')
                                <li class="dropdown scroll @if (Request::is($nav['slug'] . '/*')) active @endif">
                                    <a href="{{ $nav['url'] }}" class="dropdown-toggle" data-toggle="dropdown"> {{ strtoupper($nav['name']) }} <span class="caret"></span></a>
                                    <ul class="dropdown-menu fadeIn animated" style="overflow-y : none; " role="menu">
                                        @foreach ($navdesa->chunk(2) as $desa)
                                            @foreach ($desa as $d)
                                                <li><a href="{{ route('desa.show', ['slug' => str_slug(strtolower($d->nama))]) }}">{{ ucwords($d->sebutan_desa . ' ' . $d->nama) }}</a></li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                </li>
                            @elseif ($nav['slug'] === 'potensi')
                                <li class="dropdown @if (Request::is($nav['slug'] . '/*')) active @endif">
                                    <a href="{{ $nav['url'] }}" class="dropdown-toggle" data-toggle="dropdown"> {{ strtoupper($nav['name']) }} <span class="caret"></span></a>
                                    <ul class="dropdown-menu fadeIn animated" role="menu">
                                        @foreach ($navpotensi as $d)
                                            <li><a href="{{ route('potensi.kategori', ['slug' => $d->slug]) }}">{{ ucfirst($d->nama_kategori) }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="@if (Request::is($nav['slug'])) active @endif"><a href="{{ $nav['url'] }}">{{ strtoupper($nav['name']) }} @if ($nav['slug'] === 'beranda')
                                            <span class="sr-only">(current)</span>
                                        @endif
                                    </a></li>
                            @endif
                        @endif
                    @endforeach

                    @if (auth()->guest())
                        <li><a href="{{ route('login') }}">LOGIN<span class="sr-only">(current)</span></a></li>
                    @else
                        <li><a href="{{ route('dashboard') }}">ADMIN</a></li>
                        <li><a id="loggout" href="{{ route('logout') }}">LOGOUT<span class="sr-only">(current)</span></a></li>
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

@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('#loggout').click(function(e) {
                e.preventDefault();
                $('#logout-form').submit();
            });


        });
    </script>
@endpush
