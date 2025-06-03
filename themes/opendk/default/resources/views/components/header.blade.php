<style>
    /* Default posisi submenu ke kanan */
    .dropdown-submenu {
        position: relative;
    }
    
    .dropdown-submenu > .dropdown-menu {
        position: absolute;
        top: 0;
        left: 100%; /* Default arah ke kanan */
        margin-top: -6px;
        display: none; /* Tersembunyi secara default */
        z-index: 1000;
        min-width: 250px; /* Lebar minimum menu */
        overflow-x: auto; /* Aktifkan scroll horizontal */
        max-height: 300px; /* Batas tinggi maksimum */
        overflow-y: auto; /* Scroll jika tinggi melebihi batas */
    }
    
    /* Submenu jika mendekati tepi kanan layar, pindahkan ke kiri */
    .dropdown-submenu > .dropdown-menu.align-left {
        left: auto;
        right: 100%; /* Geser ke kiri */
    }
    
    /* Submenu tetap di kanan */
    .dropdown-submenu > .dropdown-menu.align-right {
        left: 100%; /* Tetap di kanan */
        right: auto;
    }
    
    /* Tampilkan submenu saat hover */
    .dropdown-submenu:hover > .dropdown-menu {
        display: block;
    }
    
    /* Animasi transisi */
    .dropdown-menu {
        transition: all 0.3s ease-in-out;
        overflow-x: auto; /* Tambahkan scroll horizontal jika diperlukan */
    }
    
    /* Penyesuaian padding untuk submenu */
    .navbar .navbar-nav .dropdown-menu > li > a {
        padding: 8px 20px;
        white-space: nowrap; /* Hindari teks terpotong */
    }
    
    /* Tambahkan style agar menu tidak terpotong di bawah */
    .dropdown-menu {
        white-space: nowrap;
    }
    
    /* Untuk scrollbar horizontal pada menu */
    .dropdown-menu::-webkit-scrollbar {
        height: 8px;
    }
    
    .dropdown-menu::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }
    
    .dropdown-menu::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
</style>

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
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    @foreach ($navmenus as $nav)
                    <li class="{{ $nav['children']->count() > 0 ? 'dropdown' : '' }}">
                        <a 
                        href="{{ $nav['url'] ? url($nav['url']) : '#' }}" 
                        target="{{ $nav['target'] }}" 
                        class="{{ $nav['children']->count() > 0 ? 'dropdown-toggle' : '' }}" 
                        data-toggle="{{ $nav['children']->count() > 0 ? 'dropdown' : '' }}" 
                        role="button" 
                        aria-haspopup="true" 
                        aria-expanded="false"
                        >
                        {{ $nav['name'] }} 
                        @if ($nav['children']->count() > 0)
                            <span class="caret"></span>
                        @endif
                    </a>
                    @if ($nav['children']->count() > 0)
                        <ul class="dropdown-menu">
                            @foreach ($nav['children'] as $child)
                            @if ($nav['children']->count() > 0)
                            <li class="dropdown-submenu">
                                <a 
                                href="{{ $child['url'] ? url($child['url']) : '#' }}" 
                                target="{{ $child['target'] }}"
                                tabindex="-1" class="dropdown-submenu-toggle"
                                >
                                    {{ $child['name'] }}
                                    @if ($child['children']->count() > 0)
                                        <span class="caret"></span>
                                    @endif
                                </a>

                                @if ($child['children']->count() > 0)
                                <ul class="dropdown-menu">
                                    @foreach ($child['children'] as $child1)
                                    @if ($child1['children']->count() > 0)
                                    <li class="dropdown-submenu">
                                    <a 
                                    href="{{ $child1['url'] ? url($child1['url']) : '#' }}" 
                                    target="{{ $child1['target'] }}"
                                    tabindex="-1" class="dropdown-submenu-toggle"
                                    >
                                        {{ $child1['name'] ? $child1['name'] : '#' }}

                                        @if ($child1['children']->count() > 0)
                                            <span class="caret"></span>
                                        @endif
                                    </a>
                                    </li>
                                    @else
                                    <li class="dropdown-submenu"><a href="{{ url($child1['url']) }}" target="{{ $child1['target'] }}">{{ $child1['name'] }}<span class="sr-only">(current)</span></a></li>
                                    @endif
                                    @endforeach
                                </ul>

                                @endif
                            </li>
                            @else
                            <li class="dropdown-submenu"><a href="{{ url($nav['url']) }}" target="{{ $nav['target'] }}">{{ $nav['name'] }}<span class="sr-only">(current)</span></a></li>
                            @endif
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach

                    @if (auth()->guest())
                        <li><a href="{{ route('login') }}">LogIn<span class="sr-only">(current)</span></a></li>
                    @else
                        <li><a href="{{ route('dashboard') }}">Admin</a></li>
                        <li><a id="loggout" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">LogOut<span class="sr-only">(current)</span></a></li>
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


<script>
    $(document).ready(function () {
        // Fungsi untuk memindahkan submenu jika keluar dari layar
        $('.dropdown-submenu').on('mouseenter', function () {
            var $submenu = $(this).children('.dropdown-menu');

            // Reset posisi submenu
            $submenu.removeClass('align-left align-right');

            // Hitung posisi submenu
            var submenuOffset = $submenu.offset();
            var submenuWidth = $submenu.outerWidth();
            var windowWidth = $(window).width();

            // Deteksi apakah submenu keluar dari layar
            if (submenuOffset.left + submenuWidth > windowWidth) {
                $submenu.addClass('align-left'); // Pindahkan ke kiri
            } else {
                $submenu.addClass('align-right'); // Tetap di kanan
            }

            // Tampilkan submenu
            $submenu.show();
        });

        // Sembunyikan submenu saat mouse keluar
        $('.dropdown-submenu').on('mouseleave', function () {
            $(this).children('.dropdown-menu').hide();
        });

        // Sembunyikan semua submenu level 3 jika level 1 dihover
        $('.dropdown').on('mouseenter', function () {
            $('.dropdown-submenu > .dropdown-menu').hide(); // Sembunyikan semua level 3
        });
    });
</script>