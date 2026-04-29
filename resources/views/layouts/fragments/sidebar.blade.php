@php $user = auth()->user(); @endphp
@php $sebutan_wilayah = $sebutan_wilayah ?? 'Kecamatan'; @endphp

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <center>
            <!-- <div class="user-panel"> -->
            <img class="user-image" src="{{ is_logo($profil->file_logo) }}" alt="OpenDK" width="42px"
                style="margin: 5px;">
            <p class="sidebar-kabupaten" style="font-size: 12px; color:white">
                {{ strtoupper('Pemerintah Kab. ' . $profil->nama_kabupaten) }}<br>
                {{ strtoupper('Kecamatan ' . $profil->nama_kecamatan) }}<br>
            </p>
            <!-- </div> -->
        </center>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            @if (isset($user))
                <li class="header">MENU ADMINISTRATOR</li>
                <li class="{{ Request::is(['dashboard']) ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" title="Dashboard">
                        <i class="fa fa-dashboard"></i><span>Dashboard</span>
                    </a>
                </li>

                <li class="treeview {{ Request::is(['informasi/*']) ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-archive"></i> <span>Informasi</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        @can('access.informasi.prosedur')
                            <li {{ Request::is(['informasi/prosedur*']) ? 'class=active' : '' }}>
                                <a href="{{ route('informasi.prosedur.index') }}"><i class="fa fa-circle-o"></i>Prosedur</a>
                            </li>
                        @endcan
                        @can('access.informasi.regulasi')
                            <li {{ Request::is(['informasi/regulasi*']) ? 'class=active' : '' }}>
                                <a href="{{ route('informasi.regulasi.index') }}"><i class="fa fa-circle-o"></i>Regulasi</a>
                            </li>
                        @endcan
                        @can('access.informasi.potensi')
                            <li {{ Request::is(['informasi/potensi*']) ? 'class=active' : '' }}><a
                                    href="{{ route('informasi.potensi.index') }}"><i class="fa fa-circle-o"></i>Potensi</a>
                            </li>
                        @endcan
                        @can('access.informasi.event')
                            <li {{ Request::is(['informasi/event*']) ? 'class=active' : '' }}>
                                <a href="{{ route('informasi.event.index') }}"><i class="fa fa-circle-o"></i>Event</a>
                            </li>
                        @endcan
                        @can('access.informasi.artikel')
                            <li {{ Request::is(['informasi/artikel*']) ? 'class=active' : '' }}>
                                <a href="{{ route('informasi.artikel.index') }}"><i class="fa fa-circle-o"></i>Artikel</a>
                            </li>
                        @endcan
                        @can('access.informasi.artikel_kategori')
                            <li {{ Request::is(['informasi/kategori*']) ? 'class=active' : '' }}>
                                <a href="{{ route('informasi.artikel-kategori.index') }}"><i
                                        class="fa fa-circle-o"></i>Artikel
                                    Kategori</a>
                            </li>
                            <li {{ Request::is(['informasi/komentar-artikel*']) ? 'class=active' : '' }}>
                                <a href="{{ route('informasi.komentar-artikel.index') }}"><i
                                        class="fa fa-circle-o"></i>Komentar
                                    Artikel</a>
                            </li>
                        @endcan
                        @can('access.informasi.faq')
                            <li {{ Request::is(['informasi/faq*']) ? 'class=active' : '' }}>
                                <a href="{{ route('informasi.faq.index') }}"><i class="fa fa-circle-o"></i>FAQ</a>
                            </li>
                        @endcan
                        @can('access.informasi.form_dokumen')
                            <li {{ Request::is(['informasi/form-dokumen*']) ? 'class=active' : '' }}>
                                <a href="{{ route('informasi.form-dokumen.index') }}"><i
                                        class="fa fa-circle-o"></i>Dokumen</a>
                            </li>
                        @endcan
                        @can('access.informasi.media_sosial')
                            <li {{ Request::is(['informasi/media-sosial*']) ? 'class=active' : '' }}>
                                <a href="{{ route('informasi.media-sosial.index') }}"><i class="fa fa-circle-o"></i>Media
                                    Sosial</a>
                            </li>
                        @endcan
                        @can('access.informasi.media_terkait')
                            <li {{ Request::is(['informasi/media.terkait*']) ? 'class=active' : '' }}>
                                <a href="{{ route('informasi.media.terkait') }}"><i class="fa fa-circle-o"></i>Media
                                    Terkait</a>
                            </li>
                        @endcan
                        @can('access.informasi.sinergi_program')
                            <li {{ Request::is(['informasi/sinergi-program*']) ? 'class=active' : '' }}>
                                <a href="{{ route('informasi.sinergi-program.index') }}"><i
                                        class="fa fa-circle-o"></i>Sinergi
                                    Program</a>
                            </li>
                        @endcan
                    </ul>
                </li>


                @can('access.publikasi')
                    <li class="treeview {{ Request::is(['admin/publikasi/*']) ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-picture-o"></i> <span>Publikasi</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            @can('access.publikasi.album')
                                <li {{ Request::is(['admin/publikasi/album*']) ? 'class=active' : '' }}>
                                    <a href="{{ route('publikasi.album.index') }}"><i class="fa fa-circle-o"></i>Album</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('access.kerjasama')
                    <li class="treeview {{ Request::is(['kerjasama/*']) ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-handshake-o"></i> <span>Kerjasama</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li {{ Request::is(['kerjasama/pendaftaran-kerjasama*']) ? 'class=active' : '' }}>
                                <a href="{{ route('kerjasama.pendaftaran.kerjasama') }}"><i
                                        class="fa fa-circle-o"></i>Pendaftaran Kerja Sama</a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('access.data')
                    <li class="treeview {{ Request::is(['data/*']) ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-database"></i> <span>Data</span><span
                                class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            @can('access.data.profil')
                                <li class="treeview {{ Request::is(['data/profil*']) ? 'active' : '' }}">
                                    <a href="#"><i class="fa fa-circle-o"></i>{{ $sebutan_wilayah }}
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li {{ Request::is(['data/profil*']) ? 'class=active' : '' }}>
                                            <a href="{{ route('data.profil.index') }}"><i class="fa fa-circle-o"></i>Profil</a>
                                        </li>
                                        <li {{ Request::is(['data/data-umum*']) ? 'class=active' : '' }}>
                                            <a href="{{ route('data.data-umum.index') }}"><i class="fa fa-circle-o"></i>Data
                                                Umum</a>
                                        </li>
                                        <li {{ Request::is(['data/data-desa*']) ? 'class=active' : '' }}>
                                            <a href="{{ route('data.data-desa.index') }}"><i class="fa fa-circle-o"></i>Data
                                                {{ config('setting.sebutan_desa') }}</a>
                                        </li>
                                        <li {{ Request::is(['data/data-sarana*']) ? 'class=active' : '' }}>
                                            <a href="{{ route('data.data-sarana.index') }}"><i class="fa fa-circle-o"></i>Data
                                                Sarana</a>
                                        </li>
                                        <li
                                            class="treeview {{ Request::is(['data/jabatan*', 'data/pengurus*']) ? 'active' : '' }}">
                                            <a href="#"><i class="fa fa-circle-o"></i>Perangkat Kecamatan
                                                <span class="pull-right-container">
                                                    <i class="fa fa-angle-left pull-right"></i>
                                                </span>
                                            </a>
                                            <ul class="treeview-menu">
                                                <li {{ Request::is(['data/pengurus*']) ? 'class=active' : '' }}>
                                                    <a href="{{ route('data.pengurus.index') }}"><i
                                                            class="fa fa-circle-o"></i>Pengurus</a>
                                                </li>
                                                <li {{ Request::is(['data/jabatan*']) ? 'class=active' : '' }}>
                                                    <a href="{{ route('data.jabatan.index') }}"><i
                                                            class="fa fa-circle-o"></i>Jabatan</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                    @endif
                    @can('access.data.keluarga')
                        <li
                            class="treeview {{ Request::is(['data/penduduk*', 'data/keluarga*', 'data/data-suplemen*']) ? 'active' : '' }}">
                            <a href="#"><i class="fa fa-circle-o"></i>Kependudukan
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @can('access.data.penduduk')
                                    <li {{ Request::is(['data/penduduk*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.penduduk.index') }}"><i class="fa fa-circle-o"></i>Penduduk</a>
                                    </li>
                                @endcan
                                @can('access.data.keluarga')
                                    <li {{ Request::is(['data/keluarga*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.keluarga.index') }}"><i class="fa fa-circle-o"></i>Keluarga</a>
                                    </li>
                                @endcan
                                @can('access.data.data_suplemen')
                                    <li {{ Request::is(['data/data-suplemen*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.data-suplemen.index') }}"><i class="fa fa-circle-o"></i>Data
                                            Suplemen</a>
                                    </li>
                                @endcan
                                @can('access.data.laporan_penduduk')
                                    <li {{ Request::is(['data/laporan-penduduk*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.laporan-penduduk.index') }}"><i class="fa fa-circle-o"></i>Laporan
                                            Penduduk</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan

                    @can('access.data.imunisasi')
                        <li
                            class="treeview {{ Request::is(['data/aki-akb*', 'data/imunisasi*', 'data/epidemi-penyakit*', 'data/toilet-sanitasi*']) ? 'active' : '' }}">
                            <a href="#"><i class="fa fa-circle-o"></i>Kesehatan
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @can('access.data.aki_akb')
                                    <li {{ Request::is(['data/aki-akb*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.aki-akb.index') }}"><i class="fa fa-circle-o"></i>AKI & AKB</a>
                                    </li>
                                @endcan
                                @can('access.data.imunisasi')
                                    <li {{ Request::is(['data/imunisasi*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.imunisasi.index') }}"><i class="fa fa-circle-o"></i>Imunisasi</a>
                                    </li>
                                @endcan
                                @can('access.data.epidemi_penyakit')
                                    <li {{ Request::is(['data/epidemi-penyakit*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.epidemi-penyakit.index') }}"><i class="fa fa-circle-o"></i>Epidemi
                                            Penyakit</a>
                                    </li>
                                @endcan
                                @can('access.data.toilet_sanitasi')
                                    <li {{ Request::is(['data/toilet-sanitasi*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.toilet-sanitasi.index') }}"><i class="fa fa-circle-o"></i>Toilet
                                            & Sanitasi</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan

                    @can('access.data.putus_sekolah')
                        <li
                            class="treeview {{ Request::is(['data/tingkat-pendidikan*', 'data/putus-sekolah*', 'data/fasilitas-paud*']) ? 'active' : '' }}">
                            <a href="#"><i class="fa fa-circle-o"></i>Pendidikan
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @can('access.data.tingkat_pendidikan')
                                    <li {{ Request::is(['data/tingkat-pendidikan*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.tingkat-pendidikan.index') }}"><i class="fa fa-circle-o"></i>Tingkat
                                            Pendidikan</a>
                                    </li>
                                @endcan
                                @can('access.data.putus_sekolah')
                                    <li {{ Request::is(['data/putus-sekolah*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.putus-sekolah.index') }}"><i class="fa fa-circle-o"></i>Siswa
                                            Putus Sekolah</a>
                                    </li>
                                @endcan
                                @can('access.data.fasilitas_paud')
                                    <li {{ Request::is(['data/fasilitas-paud*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.fasilitas-paud.index') }}"><i class="fa fa-circle-o"></i>Fasilitas
                                            PAUD</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan

                    @can('access.data.program_bantuan')
                        <li {{ Request::is(['data/program-bantuan*']) ? 'class=active' : '' }}>
                            <a href="{{ route('data.program-bantuan.index') }}"><i class="fa fa-circle-o"></i>Program
                                Bantuan</a>
                        </li>
                    @endcan

                    @can('access.data.anggaran_realisasi')
                        <li
                            class="treeview {{ Request::is(['data/anggaran-realisasi*', 'data/anggaran-desa*', 'data/laporan-apbdes*']) ? 'active' : '' }}">
                            <a href="#"><i class="fa fa-circle-o"></i>Finansial
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @can('access.data.anggaran_realisasi')
                                    <li {{ Request::is(['data/anggaran-realisasi*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.anggaran-realisasi.index') }}"><i class="fa fa-circle-o"></i>Anggaran
                                            & Realisasi</a>
                                    </li>
                                @endcan

                                @can('access.data.anggaran_desa')
                                    <li {{ Request::is(['data/anggaran-desa*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.anggaran-desa.index') }}"><i class="fa fa-circle-o"></i>APBDes</a>
                                    </li>
                                @endcan

                                @can('access.data.laporan_apbdes')
                                    <li {{ Request::is(['data/laporan-apbdes*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('data.laporan-apbdes.index') }}"><i class="fa fa-circle-o"></i>Laporan
                                            APBDes</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan

                    @can('access.data.pembangunan')
                        <li {{ Request::is(['data/pembangunan*']) ? 'class=active' : '' }}>
                            <a href="{{ route('data.pembangunan.index') }}"><i class="fa fa-circle-o"></i>Pembangunan</a>
                        </li>
                    @endcan

                    @can('access.data.lembaga')
                        <li class="treeview {{ Request::is(['data/kategori-lembaga*', 'data/lembaga*']) ? 'active' : '' }}">
                            <a href="#"><i class="fa fa-circle-o"></i>Lembaga
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li {{ Request::is(['data/lembaga*']) ? 'class=active' : '' }}>
                                    <a href="{{ route('data.lembaga.index') }}"><i class="fa fa-circle-o"></i>Lembaga</a>
                                </li>
                                <li {{ Request::is(['data/kategori-lembaga*']) ? 'class=active' : '' }}>
                                    <a href="{{ route('data.kategori-lembaga.index') }}"><i class="fa fa-circle-o"></i>Kategori
                                        Lembaga</a>
                                </li>
                            </ul>
                        </li>
                        @endif

                    </ul>
                    </li>
                    @endif
                    @can('access.admin_komplain')
                        <li class="treeview {{ Request::is(['admin-komplain*']) ? 'active' : '' }}"><a href="#"><i
                                    class="fa fa-comments-o"></i> <span>Admin SIKEMA</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li {{ Request::is(['admin-komplain*']) ? 'class=active' : '' }}><a
                                        href="{{ route('admin-komplain.index') }}"><i class="fa fa-circle-o"></i>Daftar
                                        Keluhan</a></li>
                                <li {{ Request::is(['admin-komplain/statistik*']) ? 'class=active' : '' }}><a
                                        href="{{ route('admin-komplain.statistik') }}"><i class="fa fa-circle-o"></i>Statistik</a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('access.pesan')
                        <li class="treeview {{ Request::is(['pesan*']) ? 'active' : '' }}">
                            <a href="#" title="pesan"><i class="fa fa-envelope"></i> <span>Pesan</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li {{ Request::is(['pesan', 'pesan/masuk']) ? 'class=active' : '' }}><a
                                        href="{{ route('pesan.index') }}"><i class="fa fa-envelope-o"></i>Pesan Masuk</a>
                                </li>
                                <li {{ Request::is(['pesan/keluar']) ? 'class=active' : '' }}><a
                                        href="{{ route('pesan.keluar') }}"><i class="fa fa-envelope-open"></i>Pesan
                                        Keluar</a></li>
                                <li {{ Request::is(['pesan/arsip']) ? 'class=active' : '' }}><a
                                        href="{{ route('pesan.arsip') }}"><i class="fa fa-archive"></i>Arsip</a></li>
                            </ul>
                        </li>
                    @endcan

                    @can('access.surat')
                        <li class="treeview {{ Request::is(['surat*']) ? 'active' : '' }}">
                            <a href="#" title="Layanan Surat"><i class="fa fa-envelope"></i> <span>Layanan
                                    Surat</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li {{ Request::is(['surat/permohonan*']) ? 'class=active' : '' }}><a
                                        href="{{ route('surat.permohonan') }}"><i class="fa fa-files-o"></i>Permohonan</a>
                                </li>
                                <li {{ Request::is(['surat/arsip*']) ? 'class=active' : '' }}><a
                                        href="{{ route('surat.arsip') }}"><i class="fa fa-folder-open"></i>Arsip</a>
                                </li>
                                <li {{ Request::is(['surat/pengaturan*']) ? 'class=active' : '' }}><a
                                        href="{{ route('surat.pengaturan') }}"><i class="fa fa-gear"></i>Pengaturan</a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('access.setting')
                        <li class="treeview {{ Request::is(['setting*', 'otp/*', '2fa/*', 'otp-2fa*']) ? 'active' : '' }}">
                            <a href="#"><i class="fa fa-cogs"></i> <span>Pengaturan</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @can('access.setting.nav_menu')
                                    <li {{ Request::is(['setting.navmenu*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('setting.navmenu.index') }}"><i class="fa fa-circle-o"></i>Menu</a>
                                    </li>
                                @endcan
                                @can('access.setting.widget')
                                    <li {{ Request::is(['setting.widget*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('setting.widget') }}"><i class="fa fa-circle-o"></i>Widget</a>
                                    </li>
                                @endcan
                                @can('access.setting.tipe_potensi')
                                    <li {{ Request::is(['setting/tipe-potensi*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('setting.tipe-potensi.index') }}"><i class="fa fa-circle-o"></i>Kategori
                                            Potensi</a>
                                    </li>
                                @endcan
                                @can('access.setting.komplain_kategori')
                                    <li {{ Request::is(['setting/komplain-kategori*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('setting.komplain-kategori.index') }}"><i class="fa fa-circle-o"></i>Kategori
                                            Komplain</a>
                                    </li>
                                @endcan
                                @can('access.setting.tipe_regulasi')
                                    <li {{ Request::is(['setting/tipe-regulasi*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('setting.tipe-regulasi.index') }}"><i class="fa fa-circle-o"></i>Tipe
                                            Regulasi</a>
                                    </li>
                                @endcan
                                @can('access.setting.jenis_penyakit')
                                    <li {{ Request::is(['setting/jenis-penyakit*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('setting.jenis-penyakit.index') }}"><i class="fa fa-circle-o"></i>Jenis
                                            Penyakit</a>
                                    </li>
                                @endcan
                                @can('access.setting.jenis_dokumen')
                                    <li {{ Request::is(['setting/jenis-dokumen*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('setting.jenis-dokumen.index') }}"><i class="fa fa-circle-o"></i>Jenis
                                            Dokumen</a>
                                    </li>
                                @endcan
                                @can('access.setting.slide')
                                    <li {{ Request::is(['setting/slide*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('setting.slide.index') }}"><i class="fa fa-circle-o"></i>Slide</a>
                                    </li>
                                @endcan
                                @can('access.setting.coa')
                                    <li {{ Request::is(['setting/coa*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('setting.coa.index') }}"><i class="fa fa-circle-o"></i>COA</a>
                                    </li>
                                @endcan
                                @can('access.setting.role')
                                    <li {{ Request::is(['setting/role*']) ? 'class=active' : '' }}>
                                        <a href="{{ route('setting.role.index') }}"><i class="fa fa-circle-o"></i>Grup
                                            Pengguna</a>
                                    </li>
                                @endcan
                                @can('access.setting.themes')
                                    <li {{ Request::is(['setting/themes*']) ? 'class=active' : '' }}><a
                                            href="{{ route('setting.themes.index') }}"><i class="fa fa-circle-o"></i>Themes</a>
                                    </li>
                                @endcan
                                @can('access.setting.user')
                                    <li {{ Request::is(['setting/user*']) ? 'class=active' : '' }}><a
                                            href="{{ route('setting.user.index') }}"><i class="fa fa-circle-o"></i>Pengguna</a>
                                    </li>
                                @endcan
                                <li {{ Request::is(['profile/password']) ? 'class=active' : '' }}>
                                    <a href="{{ route('profile.password') }}">
                                        <i class="fa fa-circle-o"></i> Ganti Password
                                    </a>
                                </li>
                                <li {{ Request::is(['otp-2fa*', 'otp/*', '2fa/*']) ? 'class=active' : '' }}>
                                    <a href="{{ route('otp2fa.index') }}">
                                        <i class="fa fa-circle-o"></i> OTP & 2FA
                                    </a>
                                </li>
                                @can('access.setting.aplikasi')
                                    <li {{ Request::is(['setting/aplikasi*']) ? 'class=active' : '' }}><a
                                            href="{{ route('setting.aplikasi.index') }}"><i class="fa fa-circle-o"></i>Aplikasi</a></li>
                                @endcan
                                @can('access.setting.info_sistem')
                                    <li {{ Request::is(['setting/info-sistem*']) ? 'class=active' : '' }}><a
                                            href="{{ route('setting.info-sistem') }}"><i class="fa fa-circle-o"></i>Info
                                            Sistem</a></li>
                                @endcan
                                @can('access.setting.database')
                                    <li {{ Request::is(['setting/backup-database', 'setting/restore-database']) ? 'class=active' : '' }}>
                                        <a href="{{ route('setting.pengaturan-database.backup') }}"><i
                                                class="fa fa-circle-o"></i>Pengaturan Database</a>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                    @endcan
                    @endif
                    @can('access.counter')
                        <li class="header">VISITOR COUNTER</li>
                        <li class="{{ Request::is(['counter']) ? 'active' : '' }}">
                            <a href="@if (isset($user)) {{ route('counter.index') }}@else {{ '#' }} @endif"
                                title="Jumlah Pengunjung"><i class="fa fa-bullhorn"></i> <span>Total Pengunjung</span>
                                <span class="pull-right-container">
                                    {{-- <small class="label pull-right bg-red">{{ Counter::allVisitors() }}</small> --}}
                                    <small class="label pull-right bg-red">{{ \App\Models\Visitor::countAllVisitors() }}</small>
                                </span>
                            </a>
                        </li>
                    @endcan

                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>
