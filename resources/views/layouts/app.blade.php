<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control: no-store" content="public">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-TileColor" content="#ffc40d">
    <meta name="theme-color" content="#1a2035">

    <title>{{ $page_title ?? config('app.name', 'Laravel') }} | {{ $browser_title }}</title>
    <meta name="description" content="{{ $page_description ?? '' }}.">
    <link rel="canonical" href="{{ Request::url() }}">
    <meta itemprop="name" content="{{ $page_title ?? '' }}">
    <meta itemprop="description" content="{{ $page_description ?? '' }}.">
    <meta itemprop="image" content="{{ asset('/icon/social.png?auto=format&amp;fit=max&amp;w=1200') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--
    <meta name="user-token" content="{{ Auth::user()->api_token }}" /> --}}
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:site_name" content="{{ \URL::to('')}}">
    <meta property="og:title" content="{{ $page_title ?? '' }}">
    <meta property="og:description" content="{{ $page_description ?? '' }}. ">
    <meta property="og:image" content="{{ asset('/icon/social.png?auto=format&amp;fit=max&amp;w=1200') }}">
    <meta property="og:image:alt" content>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page_title ?? '' }}">
    <meta name="twitter:description" content="{{ $page_description ?? '' }}. ">
    <meta name="twitter:image" content="{{ asset('/icon/social.png?auto=format&amp;fit=max&amp;w=1200') }}">
    <link rel="alternate" href="/feed.xml" type="application/atom+xml" data-title="{{ Request::url() }}">

    <link rel="icon" type="image/icon" href="@if(isset($profil_wilayah->file_logo)) {{  asset($profil_wilayah->file_logo) }} @else {{ asset('/favicon.png') }}@endif" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/icon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/icon/favicon-16x16.png') }}">
    <link rel="mask-icon" href="{{ asset('/icon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}" />
    
    <link href="{{ asset('/css/placeholder-loading.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('/css/slider.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,600,600i,700,700i|Roboto+Condensed:400,700,700i|Roboto:400,400i,500,500i,700,700i&display=swap"
        rel="stylesheet">
        <script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
</head>

<body class="hold-transition skin-blue layout-top-nav">
    @include('partialspage.preloader')
    <!-- overlay !-->
    <div class="wrapper">
        <div id="search" class="fades animated">
            <a href="#" class="close-btn" id="close-search">
                <em class="fa fa-times"></em>
            </a>
            <form method="get" action="{{ url('/') }}" role="search">
                <input class="form-control" placeholder="Mulai Pencarian" name="cari" id="searchbox" type="text" />
            </form>
        </div>
        <!--- /overlay -->
        @include('layouts.frontends.topheader')
        @include('layouts.frontends.header')
        <div class="content-wrapper">
                @include('layouts.frontends.slider')
            <div class="container">
                <!-- Main content -->
                <section class="content">
                        <div class="row">
                        @include('layouts.frontends.breadcumb')
                        @yield('content')
                        @include('layouts.frontends.sidebar')
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.container -->
        </div>
        <!-- /.content-wrapper -->
        @include('.layouts.frontends.footer')
    </div>
    <!-- ./wrapper -->
    <div class="scroll-top-wrapper ">
       <span class="scroll-top-inner">
                <i class="fa fa-2x fa-arrow-circle-up"></i>
            </span>
    </div>
    <!-- ./wrapper -->
    <!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery 3 -->
    <script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
        <script>
		setTimeout(function(){
		$('.preloader_bg').fadeToggle();
		}, 500);
	</script>
    <script src="{{ asset('/bower_components/jquery/dist/jquery.socialShare.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/bower_components/admin-lte/dist/js/adminlte.min.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('/js/custom.js') }}"></script>
    @stack('scripts')
</body>
</html>
