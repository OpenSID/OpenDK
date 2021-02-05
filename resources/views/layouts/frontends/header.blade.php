<header id="navbar"  class="main-header">
    <nav class="navbar  navbar-static-top">
      <div class="container">
        <div class="navbar-header">
<<<<<<< HEAD
            <a href="{{ route('beranda')}}"  class="navbar-brand"><img  src="{{ asset("/img/logo.png")}}" style="margin-top:-5px; display:flex" alt="KD" id="logo-brand" width="180px"></a>
=======
            <a href="{{ route('beranda')}}"  class="navbar-brand"><img  src="{{ asset("/img/logo-coba.png")}}" alt="KD" id="logo-brand" width="100px"></a>
>>>>>>> 2890337063ab134daf3e7f211cd0f029924addf1
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
<<<<<<< HEAD
            <li class="dropdown active @if(Request::is('/')) active @endif"><a href="{{ route('beranda') }}">BERANDA <span class="sr-only">(current)</span></a></li>
=======
            <li class="dropdown @if(Request::is('/'))active @endif"><a href="{{ route('beranda') }}">BERANDA <span class="sr-only">(current)</span></a></li>
>>>>>>> 2890337063ab134daf3e7f211cd0f029924addf1
            <li class="dropdown menu-large">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"> PROFIL <span class="caret"></span></a>
              <ul class="dropdown-menu megamenu row fadeIn animated">
                <li class="col-md-4 col-sm-2">
                  <ul class="mega-list">
<<<<<<< HEAD
                    <li class="@if(Request::is('profil/sejarah-*')) active @endif"> <a class="text-bold" href="{{ route('profil.sejarah', ['wilayah' => (strtolower($nama_wilayah))] ) }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Sejarah</a></li>
=======
                    <li class="@if(Request::is('profil/sejarah-*'))active @endif"> <a class="text-bold" href="{{ route('profil.sejarah', ['wilayah' => (strtolower($nama_wilayah))] ) }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Sejarah</a></li>
>>>>>>> 2890337063ab134daf3e7f211cd0f029924addf1
                    <li class="@if(Request::is('profil/letak-geografis'))active @endif"><a href="{{ route('profil.letak-geografis') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Letak Geografis</a></li>
                    <li class="@if(Request::is('profil/struktur-pemerintahan'))active @endif"><a href="{{ route('profil.struktur-pemerintahan') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Struktur Pemerintahan</a></li>
                    <li class="@if(Request::is('profil/visi-dan-misi'))active @endif"><a href="{{ route('profil.visi-misi') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Visi & Misi</a></li>
                  </ul>
                </li>
                <li class="col-md-4 col-sm-4">
<<<<<<< HEAD
                  <h4 class="text-bold text-center">Sambutan Kepala {{ $sebutan_wilayah }} {{ $nama_wilayah }}</h4> 
                  <small style="text-align:justify;">{{ strip_tags($profil_wilayah->sambutan) }} </small>
                </li>
                <li class="col-md-4 col-sm-3 text-center">
                  <img src="@if(isset($profil_wilayah->foto_kepala_wilayah)) {{ asset($profil_wilayah->foto_kepala_wilayah) }} @else  @endif" width="200px" class="img-user">
=======
                  <h4 class="text-bold text-center">Sambutan Kepala {{ $sebutan_wilayah }} {{ $nama_wilayah }}</h4> Alhamdulillahhirobbilalamin
                  <small style="text-align:justify; font-size:12px;"> Dengan mengucapkan puji dan syukur kehadirat Allah SWT, kami sampaikan salam hangat bagi warga {{ $sebutan_wilayah }} {{ $nama_wilayah }}. Selanjutnya dengan senang hati kami sampaikan informasi kepada Anda untuk mengenal lebih dekat {{ $sebutan_wilayah }} {{ $nama_wilayah }} melalui situs resmi {{ $sebutan_wilayah }} {{ $nama_wilayah }} ini. Situs ini diharapkan akan memberikan informasi mengenai {{ $sebutan_wilayah }} {{ $nama_wilayah }} secara umum tentang pemerintahan, pembangunan dan kemasyarakatan, termasuk didalamnya, khasanah budaya, potensi ekonomi dan pariwisata, yang selalanjutnya dapat digunakan dinas instansi terkait dan stakeholder lainnya dalam rangka pelaksanaan program-progam pembangunan di {{ $sebutan_wilayah }} {{ $nama_wilayah }} untuk meningkatkan kesejahteraan masyarakat. </small>
                </li>
                <li class="col-md-4 col-sm-3 text-center">
                  <img src="{{ asset('/uploads/user/user1-128x128.jpg') }}" width="200px" class="img-user">
>>>>>>> 2890337063ab134daf3e7f211cd0f029924addf1
                  <h6 class="text-bold no-padding">{{ $profil_wilayah->nama_camat }}</h6>
                  <h6>Kepala {{ $sebutan_wilayah }} {{ $nama_wilayah }} </h6>
                </li>
                <li style="margin-left:-50px" class="col-sm-3"></li>
              </ul>
          </li>
<<<<<<< HEAD
=======
            {{-- <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">PUBLIKASI <span class="caret"></span></a>
              <ul class="dropdown-menu fadeIn animated" role="menu">
                  <li><a href="#">Agenda Kegiatan</a></li>
                  <li><a href="#">Berita Kecamatan</a></li>
                  <li><a href="#">Berita Desa</a></li>
                </ul>
            </li> --}}
>>>>>>> 2890337063ab134daf3e7f211cd0f029924addf1
            <li class="dropdown @if(Request::is('desa-*'))active @endif">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">DESA <span class="caret"></span></a>
              <ul class="dropdown-menu fadeIn animated" role="menu">
                @foreach ($navdesa as $d)  
                <li><a href="{{ route('desa.show', ['slug' => str_slug(strtolower($d->nama))]) }}">{{ 'Desa ' .ucfirst($d->nama) }}</a></li>
                @endforeach
                </ul>
            </li>
            <li class="dropdown @if(Request::is('potensi*'))active @endif">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">POTENSI <span class="caret"></span></a>
              <ul class="dropdown-menu fadeIn animated" role="menu">
                @foreach ($navpotensi as $d)  
                <li><a href="{{ route('potensi.kategori', ['slug'=>$d->slug]) }}">{{ ucfirst($d->nama_kategori) }}</a></li>
                @endforeach
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">STATISTIK <span class="caret"></span></a>
              <ul class="dropdown-menu fadeIn animated" role="menu">
                  <li><a href="{{ route('statistik.kependudukan') }}">Penduduk</a></li>
                  <li><a href="{{ route('statistik.pendidikan') }}">Pendidikan</a></li>
                  <li><a href="{{ route('statistik.kesehatan') }}">Kesehatan</a></li>
                  <li><a href="{{ route('statistik.program-bantuan') }}">Program dan Bantuan</a></li>
                  <li><a href="{{ route('statistik.anggaran-dan-realisasi') }}">Anggaran dan Realisasi</a></li>
                  <li><a href="{{ route('statistik.anggaran-desa') }}">Anggaran Desa</a></li>
                </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">UNDUHAN <span class="caret"></span></a>
            <ul class="dropdown-menu fadeIn animated" role="menu">
                <li><a href="{{ route('unduhan.prosedur') }}">Prosedur</a></li>
                <li><a href="{{ route('unduhan.regulasi') }}">Regulasi</a></li>
                <li><a href="{{ route('unduhan.form-dokumen') }}">Dokumen</a></li>
              </ul>
          </li>
        </ul>
      </div>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="notifications-menu">
              <a href='#search' tooltip="t">
                <em class="fa fa-search"></em>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>