<header id="navbar"  class="main-header">
    <nav class="navbar  navbar-static-top">
      <div class="container">
        <div class="navbar-header">
            <a href="{{ route('beranda')}}"  class="navbar-brand">
              <img class="logo-kab" src="@if(isset($profil_wilayah->file_logo)) {{  asset($profil_wilayah->file_logo) }} @else {{   asset('img/logo_nav.png')}}@endif" alt="KD" >
              <small class="text-kab">PEMERINTAH {{ ucwords($nama_wilayah_kab) }}</small>
              <small class="text-kec">{{ strtoupper($sebutan_wilayah.' '.$nama_wilayah) }}</small>
            </a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown @if(Request::is('/')) active @endif"><a href="{{ route('beranda') }}">BERANDA <span class="sr-only">(current)</span></a></li>
            <li class="dropdown @if(Request::is('profil/*')) active @endif menu-large">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"> PROFIL <span class="caret"></span></a>
              <ul class="dropdown-menu megamenu row fadeIn animated">
                <li class="col-md-4 col-sm-2">
                  <ul class="mega-list">
                    <li class="@if(Request::is('profil/sejarah-*')) active @endif"> <a class="text-bold" href="{{ route('profil.sejarah', ['wilayah' => (strtolower($nama_wilayah))] ) }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Sejarah</a></li>
                    <li class="@if(Request::is('profil/letak-geografis')) active @endif"><a href="{{ route('profil.letak-geografis') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Letak Geografis</a></li>
                    <li class="@if(Request::is('profil/struktur-pemerintahan')) active @endif"><a href="{{ route('profil.struktur-pemerintahan') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Struktur Pemerintahan</a></li>
                    <li class="@if(Request::is('profil/visi-dan-misi')) active @endif"><a href="{{ route('profil.visi-misi') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Visi & Misi</a></li>
                  </ul>
                </li>
                <li class="col-md-4 col-sm-4">
                  <h4 class="text-bold text-center">Sambutan {{ $sebutan_kepala_wilayah }} {{ $nama_wilayah }}</h4> 
                  <small class="" style="text-align:justify;">{{ strip_tags($profil_wilayah->sambutan) }} </small>
                </li>
                <li class="col-md-4 col-sm-3 text-center">
                  <img src="@if(isset($profil_wilayah->foto_kepala_wilayah)) {{ asset($profil_wilayah->foto_kepala_wilayah) }} @else {{ asset('img/no-profile.png') }} @endif" width="200px" class="img-user">
                  <h6 class="text-bold no-padding">{{ $profil_wilayah->nama_camat }}</h6>
                  <h6>{{ $sebutan_kepala_wilayah }} {{ $nama_wilayah }} </h6>
                </li>
                <li style="margin-left:-50px" class="col-sm-3"></li>
              </ul>
          </li>
            <li class="dropdown @if(Request::is('desa/*'))active @endif">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">DESA <span class="caret"></span></a>
                <ul class="dropdown-menu fadeIn animated" style="min-width: 400px;" role="menu">
                  @foreach ($navdesa->chunk(2) as $desa)
                    @foreach ($desa as $d)
                    <li class="col-sm-6" style="white-space: normal;"><a href="{{ route('desa.show', ['slug' => str_slug(strtolower($d->nama))]) }}">{{ 'Desa ' .ucfirst($d->nama) }}</a></li>
                    @endforeach
                  @endforeach
                </ul>
            </li>
            <li class="dropdown @if(Request::is('potensi/*'))active @endif" >
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
            <li class="dropdown @if(Request::is('unduhan/*'))active @endif">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">UNDUHAN <span class="caret"></span></a>
            <ul class="dropdown-menu fadeIn animated" role="menu">
                <li><a href="{{ route('unduhan.prosedur') }}">Prosedur</a></li>
                <li><a href="{{ route('unduhan.regulasi') }}">Regulasi</a></li>
                <li><a href="{{ route('unduhan.form-dokumen') }}">Dokumen</a></li>
              </ul>
          </li>
          @if (Sentinel::guest())
            <li><a href="{{ route('login') }}">LOGIN<span class="sr-only">(current)</span></a></li>
          @else
          <li><a href="{{ route('logout') }}">LOGOUT<span class="sr-only">(current)</span></a></li>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>  
          @endif
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