<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-TileColor" content="#ffc40d">
    <meta name="theme-color" content="#fff">

    <title>{{ $page_title ?? '' }} | {{ config('app.name', 'Laravel') }} </title>
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
    <meta property="og:site_name" content="{{ Request::url() }}">
    <meta property="og:title" content="{{ $page_title ?? '' }}">
    <meta property="og:description" content="{{ $page_description ?? '' }}. ">
    <meta property="og:image" content="{{ asset('/icon/social.png?auto=format&amp;fit=max&amp;w=1200') }}">
    <meta property="og:image:alt" content>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page_title ?? '' }}">
    <meta name="twitter:description" content="{{ $page_description ?? '' }}. ">
    <meta name="twitter:image" content="{{ asset('/icon/social.png?auto=format&amp;fit=max&amp;w=1200') }}">
    <link rel="alternate" href="/feed.xml" type="application/atom+xml" data-title="{{ Request::url() }} feed">

    <link rel="icon" type="image/icon" href="{{ asset('/favicon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
    <link rel="mask-icon" href="/icon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}" />

    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}">
    {{--
    <link rel="stylesheet" href="{{ asset('/bower_components/Ionicons/css/ionicons.min.css') }}">
    --}}
    {{--
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/iCheck/all.css') }}">
    --}}
    @stack('css')
    @if (Route::currentRouteName() === 'beranda')
        <link rel="stylesheet" href="{{ asset('/css/slider.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,600,600i,700,700i|Roboto+Condensed:400,700,700i|Roboto:400,400i,500,500i,700,700i&display=swap"
        rel="stylesheet">
</head>

<body class="hold-transition skin-blue layout-top-nav">
    {{-- <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('service-worker.js').then(function(registration) {
                console.log('Service worker registered successfully', registration);
            }).catch(function(err) {
                console.log('Service worker registration failed: ', err);
            });
        };

    </script> --}}
    <!-- overlay !-->

    <div class="wrapper">
        <div id="search" class="fades animated">
            <a href="#" class="close-btn" id="close-search">
                <em class="fa fa-times"></em>
            </a>
            <form method="post">
                <input class="form-control" placeholder="Mulai Pencarian" id="searchbox" type="search" />
            </form>
        </div>
        <!--- /overlay -->

        @include('layouts.frontends.topheader')
        @include('layouts.frontends.header')
        {{-- @include('pages.sambutan') --}}
        <div class="content-wrapper">
            @if (Route::currentRouteName() === 'beranda')
                @include('layouts.frontends.slider')
                @include('layouts.frontends.service')
            @endif
            <div class="container">
                <!-- Main content -->
                <div class="row">
                    <section class="content container-fluid">
                        @if (Route::currentRouteName() === 'beranda')
                        @else
                            @include('layouts.frontends.breadcumb')
                        @endif
                        @yield('content')
                        @include('layouts.frontends.sidebar')
                    </section>
                </div>
                <!-- /.content -->
                {{-- @include('.layouts.frontends.instansi')
                --}}
            </div>
            <!-- /.container -->
        </div>
        <!-- /.content-wrapper -->
        @include('.layouts.frontends.footer')
    </div>

    <!-- ./wrapper -->

    <div class="scroll-top-wrapper ">
        <x-sign> <span class="scroll-top-inner">
                <i class="fa fa-2x fa-arrow-circle-up"></i>
            </span></x-sign>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery 3 -->
    <script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('/bower_components/jquery/dist/jquery.socialShare.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/bower_components/admin-lte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('/js/custom.js') }}"></script>
    @stack('scripts')
</body>

</html>
