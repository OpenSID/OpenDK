<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $page_title ?? config('app.name', 'Laravel') }} | {{ $browser_title }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ is_logo($profil->file_logo) }}" />
    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/iCheck/all.css') }}">
    @stack('css')
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/AdminLTE.min.css') }}">

    <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
    <!--
        AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/skins/skin-blue.min.css') }}">

    <!-- Admin style -->
    <link rel="stylesheet" href="{{ asset('/css/admin-style.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    {{-- filepond --}}
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">

    <style>
        .pagination {
            margin: 0;
            /* Hilangkan margin bawaan */
            vertical-align: middle;
            /* Pastikan sejajar */
        }

        .pagination li {
            vertical-align: middle;
            /* Pastikan setiap elemen di dalamnya sejajar */
        }
    </style>

    @stack('styles')

    @livewireStyles
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <!-- Main Header -->
        @include('layouts.fragments.header')
        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.fragments.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            {{ $slot }}
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('layouts.fragments.footer')

        <!-- Control Sidebar -->
        @include('layouts.fragments.control_sidebar')
    </div>
    <button class="scrollToTop btn"><i class="fa fa-arrow-up fa-lg"></i></button>
    <!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery 3 -->
    <script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/bower_components/admin-lte/dist/js/adminlte.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('/bower_components/admin-lte/plugins/iCheck/icheck.min.js') }}"></script>

    <script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- sortablejs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>

    <!-- tom select -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

    <!-- filepond -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script>
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginFileValidateSize);
        FilePond.registerPlugin(FilePondPluginImagePreview);
    </script>

    @stack('scripts')

    <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
    <script type="application/javascript">
        $(document).ready(function(){

            //Check to see if the window is top if not then display button
            $(window).scroll(function(){
                if ($(this).scrollTop() > 100) {
                    $('.scrollToTop').fadeIn();
                } else {
                    $('.scrollToTop').fadeOut();
                }
            });

            //Click event to scroll to top
            $('.scrollToTop').click(function(){
                $('html, body').animate({scrollTop : 0},800);
                return false;
            });

            window.setTimeout(function() {
                $("#notifikasi").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 5000);
        });
    </script>

    <script>
        $(document).ready(function() {

            window.addEventListener('form', event => {
                $('#myModal').modal(event.detail.param);
            });

            window.addEventListener('form-copy', event => {
                $(`#${event.detail.nameId}`).modal(event.detail.param);
            });

            window.addEventListener('modal', event => {
                $(`#${event.detail.nameId}`).modal(event.detail.param);
            });

            window.addEventListener('modalCetakSemua', event => {
                $('#myModalCetakSemua').modal(event.detail.param);
            });

            window.livewire.on('success', (message) => {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: message,
                    showConfirmButton: true,
                    timer: 1500
                })
                $('#myModal').modal('hide');
            });

            window.livewire.on('error', (message) => {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: message,
                    showConfirmButton: true,
                    timer: 1500
                })

            });
        })
    </script>

    @livewireScripts

</body>

</html>
