@php $user = auth()->user(); @endphp

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <center>
        <!-- <div class="user-panel"> -->
                <img class="user-image" src="{{ is_logo($profil->file_logo) }}" alt="OpenDK" width="42px" style="margin: 5px;">
                <p style="font-size: 12px; color:white">
                    {{ strtoupper('Pemerintah Kab. ' . $profil->nama_kabupaten) }}<br>
                    {{  strtoupper('Kecamatan ' . $profil->nama_kecamatan) }}<br>
                </p>
        <!-- </div> -->
        </center>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            @if(isset($user))
                <li class="header">MENU ADMINISTRATOR</li>
                <li class="{{ (Request::is(['dashboard'])? 'active' : '') }}">
                    <a href="@if(isset($user)){{ route('dashboard') }}@else {{ '#' }} @endif" title="Dashboard">
                        <i class="fa fa-dashboard"></i><span>Dashboard</span>
                    </a>
                </li>

                @if($user->hasrole(['super-admin', 'admin-kecamatan', 'administrator-website', 'kontributor-artikel']))
                    <li class="treeview {{ (Request::is(['informasi/*'])? 'active' : '') }}">
                        <a href="#"><i class="fa fa-archive"></i> <span>Informasi</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                        </a>
                        <ul class="treeview-menu">
                            @role('super-admin|admin-kecamatan|administrator-website')
                            <li {{ (Request::is(['informasi/prosedur/*', 'informasi/prosedur/index', 'informasi/prosedur'])? 'class=active' : '') }}>
                                <a href="{{ route('informasi.prosedur.index') }}"><i class="fa fa-circle-o"></i>Prosedur</a>
                            </li>
                            @endrole
                            @role('super-admin|admin-kecamatan|administrator-website')
                            <li {{ (Request::is(['informasi/regulasi/*', 'informasi/regulasi/index', 'informasi/regulasi'])? 'class=active' : '') }}>
                                <a href="{{ route('informasi.regulasi.index') }}"><i class="fa fa-circle-o"></i>Regulasi</a>
                            </li>
                            @endrole
                            @role('super-admin|admin-kecamatan|administrator-website')
                            <li {{ (Request::is(['informasi/potensi', 'informasi/potensi/*'])? 'class=active' : '') }}><a
                                        href="{{ route('informasi.potensi.index') }}"><i class="fa fa-circle-o"></i>Potensi</a></li>
                            @endrole
                            @role('super-admin|admin-kecamatan|administrator-website')
                            <li {{ (Request::is(['informasi/event/*', 'informasi/event/index', 'informasi/event'])? 'class=active' : '') }}>
                                <a href="{{ route('informasi.event.index') }}"><i class="fa fa-circle-o"></i>Event</a></li>
                            @endrole
                            @role('super-admin|admin-kecamatan|administrator-website|kontributor-artikel')
                            <li {{ (Request::is(['informasi/artikel/*', 'informasi/artikel/index', 'informasi/artikel'])? 'class=active' : '') }}>
                                <a href="{{ route('informasi.artikel.index') }}"><i class="fa fa-circle-o"></i>Artikel</a></li>
                            @endrole 
                            @role('super-admin|admin-kecamatan|administrator-website')
                            <li {{ (Request::is(['informasi/faq/*', 'informasi/faq/index', 'informasi/faq'])? 'class=active' : '') }}>
                                <a href="{{ route('informasi.faq.index') }}"><i class="fa fa-circle-o"></i>FAQ</a></li>
                            @endrole
                            @role('super-admin|admin-kecamatan|administrator-website')
                            <li {{ (Request::is(['informasi/form-dokumen/*', 'informasi/form-dokumen/index', 'informasi/form-dokumen'])? 'class=active' : '') }}>
                                <a href="{{ route('informasi.form-dokumen.index') }}"><i class="fa fa-circle-o"></i>Dokumen</a></li>
                            @endrole 
                        </ul>
                    </li>
                @endif
                @if(!$user->hasrole(['admin-komplain', 'kontributor-artikel']))
                    <li class="treeview {{ (Request::is(['data/*'])? 'active' : '') }}">
                        <a href="#"><i class="fa fa-database"></i> <span>Data</span><span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            @if($user->hasrole(['super-admin', 'admin-kecamatan']))
                            <li class="treeview {{ (Request::is(['data/profil/*', 'data/profil/index', 'data/profil','data/data-umum/*', 'data/data-umum/index', 'data/data-umum','data/data-desa/*', 'data/data-desa/index', 'data/data-desa'])? 'active' : '') }}">
                                <a href="#"><i class="fa fa-circle-o"></i>{{ $sebutan_wilayah }}
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li {{ (Request::is(['data/profil/*', 'data/profil/index', 'data/profil'])? 'class=active' : '') }}>
                                    <a href="{{ route('data.profil.index') }}"><i class="fa fa-circle-o"></i>Profil</a>
                                    </li>
                                    <li {{ (Request::is(['data/data-umum/*', 'data/data-umum/index', 'data/data-umum'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.data-umum.index') }}"><i class="fa fa-circle-o"></i>Data Umum</a>
                                    </li>
                                    <li {{ (Request::is(['data/data-desa/*', 'data/data-desa/index', 'data/data-desa'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.data-desa.index') }}"><i class="fa fa-circle-o"></i>Data Desa</a>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            @if($user->hasrole(['super-admin', 'admin-desa', 'admin-kecamatan']))
                                <li class="treeview {{ (Request::is(['data/penduduk/*', 'data/penduduk/index', 'data/penduduk', 'data/keluarga/*', 'data/keluarga/index', 'data/keluarga', 'data/laporan-penduduk'])? 'active' : '') }}">
                                    <a href="#"><i class="fa fa-circle-o"></i>Kependudukan
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li {{ (Request::is(['data/penduduk/*', 'data/penduduk/index', 'data/penduduk'])? 'class=active' : '') }}>
                                            <a href="{{ route('data.penduduk.index') }}"><i class="fa fa-circle-o"></i>Penduduk</a>
                                        </li>
                                        <li {{ (Request::is(['data/keluarga/*', 'data/keluarga/index', 'data/keluarga'])? 'class=active' : '') }}>
                                            <a href="{{ route('data.keluarga.index') }}"><i class="fa fa-circle-o"></i>Keluarga</a>
                                        </li>
                                        <li {{ (Request::is(['data/laporan-penduduk/*', 'data/laporan-penduduk/index', 'data/laporan-penduduk'])? 'class=active' : '') }}>
                                            <a href="{{ route('data.laporan-penduduk.index') }}"><i class="fa fa-circle-o"></i>Laporan Penduduk</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if($user->hasrole(['super-admin', 'admin-puskesmas']))
                            <li class="treeview {{ (Request::is(['data/aki-akb/*', 'data/aki-akb','data/imunisasi/*', 'data/imunisasi','data/epidemi-penyakit/*', 'data/epidemi-penyakit','data/toilet-sanitasi/*', 'data/toilet-sanitasi'])? 'active' : '') }}">
                                <a href="#"><i class="fa fa-circle-o"></i>Kesehatan
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li {{ (Request::is(['data/aki-akb/*', 'data/aki-akb'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.aki-akb.index') }}"><i class="fa fa-circle-o"></i>AKI & AKB</a>
                                    </li>
                                    <li {{ (Request::is(['data/imunisasi/*', 'data/imunisasi'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.imunisasi.index') }}"><i class="fa fa-circle-o"></i>Imunisasi</a>
                                    </li>
                                    <li {{ (Request::is(['data/epidemi-penyakit/*', 'data/epidemi-penyakit'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.epidemi-penyakit.index') }}"><i class="fa fa-circle-o"></i>Epidemi Penyakit</a>
                                    </li>
                                    <li {{ (Request::is(['data/toilet-sanitasi/*', 'data/toilet-sanitasi'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.toilet-sanitasi.index') }}"><i class="fa fa-circle-o"></i>Toilet & Sanitasi</a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if($user->hasrole(['super-admin', 'admin-pendidikan', 'administrator-website']))
                            <li class="treeview {{ (Request::is(['data/tingkat-pendidikan/*', 'data/tingkat-pendidikan', 'data/putus-sekolah/*', 'data/putus-sekolah', 'data/fasilitas-paud/*', 'data/fasilitas-paud'])? 'active' : '') }}">
                                <a href="#"><i class="fa fa-circle-o"></i>Pendidikan
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li {{ (Request::is(['data/tingkat-pendidikan/*', 'data/tingkat-pendidikan'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.tingkat-pendidikan.index') }}"><i class="fa fa-circle-o"></i>Tingkat Pendidikan</a>
                                    </li>
                                    <li {{ (Request::is(['data/putus-sekolah/*', 'data/putus-sekolah'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.putus-sekolah.index') }}"><i class="fa fa-circle-o"></i>Siswa Putus Sekolah</a>
                                    </li>
                                    <li {{ (Request::is(['data/fasilitas-paud/*', 'data/fasilitas-paud'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.fasilitas-paud.index') }}"><i class="fa fa-circle-o"></i>Fasilitas PAUD</a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if($user->hasrole(['super-admin', 'administrator-website', 'admin-desa']))
                            <li {{ (Request::is(['data/program-bantuan/*', 'data/program-bantuan/index', 'data/program-bantuan'])? 'class=active' : '') }}>
                                <a href="{{ route('data.program-bantuan.index') }}"><i class="fa fa-circle-o"></i>Program Bantuan</a></li>
                            @endif

                            @if($user->hasrole(['super-admin', 'admin-desa', 'admin-kecamatan','administrator-website']))
                            <li class="treeview {{ (Request::is(['data/anggaran-realisasi/*','data/anggaran-realisasi' ,'data/anggaran-desa/*', 'data/anggaran-desa', 'data/laporan-apbdes'])? 'active' : '') }}">
                                <a href="#"><i class="fa fa-circle-o"></i>Finansial
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    @if($user->hasrole(['super-admin', 'admin-kecamatan','administrator-website']))
                                    <li {{ (Request::is(['data/anggaran-realisasi/*', 'data/anggaran-realisasi/index', 'data/anggaran-realisasi'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.anggaran-realisasi.index') }}"><i class="fa fa-circle-o"></i>Anggaran & Realisasi</a>
                                    </li>
                                    @endif

                                    @if($user->hasrole(['super-admin', 'admin-desa','administrator-website']))
                                    <li {{ (Request::is(['data/anggaran-desa/*', 'data/anggaran-desa/index', 'data/anggaran-desa'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.anggaran-desa.index') }}"><i class="fa fa-circle-o"></i>APBDes</a></li>
                                    @endif

                                    @if($user->hasrole(['super-admin','admin-desa','administrator-website']))
                                    <li {{ (Request::is(['data/laporan-apbdes/*', 'data/laporan-apbdes/index', 'data/laporan-apbdes'])? 'class=active' : '') }}>
                                        <a href="{{ route('data.laporan-apbdes.index') }}"><i class="fa fa-circle-o"></i>Laporan APBDes</a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if($user->hasrole(['super-admin', 'admin-komplain', 'administrator-website', 'kontributor-artikel']))
                <li class="treeview {{ (Request::is(['admin-komplain/*', 'admin-komplain'])? 'active' : '') }}"><a href="#"><i class="fa fa-comments-o"></i> <span>Admin SIKEMA</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        @role('super-admin|admin-komplain|administrator-website|kontributor-artikel')
                        <li {{ (Request::is(['admin-komplain', 'admin-komplain/*'])? 'class=active' : '') }}><a href="{{ route('admin-komplain.index') }}"><i class="fa fa-circle-o"></i>Daftar Keluhan</a></li>
                        @endrole 
                        @role('super-admin|admin-komplain|administrator-website')
                        <li {{ (Request::is(['admin-komplain/statistik'])? 'class=active' : '') }}><a href="{{ route('admin-komplain.statistik') }}"><i class="fa fa-circle-o"></i>Statistik</a></li>
                        @endrole 
                    </ul>
                </li>
                @endif

                @if($user->hasrole(['super-admin', 'administrator-website']))
                <li class="treeview {{ (Request::is(['setting/*'])? 'active' : '') }}"><a href="#"><i class="fa fa-cogs"></i> <span>Pengaturan</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li {{ (Request::is(['setting/tipe-potensi/*', 'setting/tipe-potensi'])? 'class=active' : '') }}>
                          <a href="{{ route('setting.tipe-potensi.index') }}"><i class="fa fa-circle-o"></i>Kategori Potensi</a></li>
                        @if($user->hasrole(['super-admin', 'administrator-website']))
                        <li {{ (Request::is(['setting/komplain-kategori/*', 'setting/komplain-kategori'])? 'class=active' : '') }}>
                            <a href="{{ route('setting.komplain-kategori.index') }}"><i class="fa fa-circle-o"></i>Kategori
                                Komplain</a></li>
                        @endif
                        @if($user->hasrole(['super-admin', 'administrator-website']))
                        <li {{ (Request::is(['setting/tipe-regulasi/*', 'setting/tipe-regulasi'])? 'class=active' : '') }}>
                            <a href="{{ route('setting.tipe-regulasi.index') }}"><i class="fa fa-circle-o"></i>Tipe
                                Regulasi</a></li>
                        @endif
                        @if($user->hasrole(['super-admin', 'administrator-website']))
                        <li {{ (Request::is(['setting/jenis-penyakit/*', 'setting/jenis-penyakit'])? 'class=active' : '') }}>
                            <a href="{{ route('setting.jenis-penyakit.index') }}"><i class="fa fa-circle-o"></i>Jenis Penyakit</a></li>
                        @endif
                        @if($user->hasrole(['super-admin', 'administrator-website']))
                        <li {{ (Request::is(['setting/slide/*', 'setting/slide'])? 'class=active' : '') }}>
                            <a href="{{ route('setting.slide.index') }}"><i class="fa fa-circle-o"></i>Slide</a></li>
                        @endif
                        @if($user->hasrole(['super-admin', 'administrator-website']))
                        <li {{ (Request::is(['setting/coa/*', 'setting/coa'])? 'class=active' : '') }}>
                            <a href="{{ route('setting.coa.index') }}"><i class="fa fa-circle-o"></i>COA</a></li>
                        @endif
                            {{-- @if($user->hasrole('super-admin'))
                        <li {{ (Request::is(['setting/hasrole/*', 'setting/hasrole'])? 'class=active' : '') }}><a
                                    href="{{ route('setting.hasrole.index') }}"><i class="fa fa-circle-o"></i>Grup Pengguna</a></li>
                        @endif --}}
                        @if($user->hasrole(['super-admin', 'administrator-website']))
                        <li {{ (Request::is(['setting/user/*', 'setting/user'])? 'class=active' : '') }}><a
                                    href="{{ route('setting.user.index') }}"><i class="fa fa-circle-o"></i>Pengguna</a></li>
                        @endif
                        @if($user->hasrole(['super-admin', 'administrator-website']))
                        <li {{ (Request::is(['setting/aplikasi/*', 'setting/aplikasi'])? 'class=active' : '') }}><a
                                    href="{{ route('setting.aplikasi.index') }}"><i class="fa fa-circle-o"></i>Aplikasi</a></li>
                        @endif
                        @if($user->hasrole(['super-admin', 'administrator-website']))
                        <li {{ (Request::is(['setting/info-sistem/*', 'setting/info-sistem'])? 'class=active' : '') }}><a
                                    href="{{ route('setting.info-sistem') }}"><i class="fa fa-circle-o"></i>Info Sistem</a></li>
                        @endif
                    </ul>
                </li>
                @endif
            @endif
            <li class="header">VISITOR COUNTER</li>
            <li class="{{ (Request::is(['counter'])? 'active' : '') }}">
                <a href="@if(isset($user)){{ route('counter.index') }}@else {{ '#' }} @endif" title="Jumlah Pengunjung"><i class="fa fa-bullhorn"></i> <span>Total Pengunjung</span>
                    <span class="pull-right-container">
                        <small class="label pull-right bg-red">{{ Counter::allVisitors() }}</small>
                    </span>
                </a>
            </li>
            
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>