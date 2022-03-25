<header id="navbar"  class="main-header">
    <nav class="navbar  navbar-static-top">
      <div class="container">
        <div class="navbar-header">
            <a href="{{ route('beranda') }}"  class="navbar-brand">
              <img class="logo-kab" src="{{ is_logo($profil->file_logo) }}" alt="OpenDK" id="logo-brand" >
              <small class="text-kab">{{ strtoupper('PEMERINTAH KAB. ' . $profil->nama_kabupaten) }}</small>
              <small class="text-kec">{{ strtoupper($sebutan_wilayah.' '.$profil->nama_kecamatan) }}</small>
            </a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown @if(Request::is('/')) active @endif"><a href="{{ route('beranda') }}">BERANDA <span class="sr-only">(current)</span></a></li>
            <li class="dropdown @if(Request::is('berita-desa')) active @endif"><a href="{{ route('berita-desa') }}">BERITA DESA </a></li>
            <li class="dropdown @if(Request::is('profil/*')) active @endif menu-large">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"> PROFIL <span class="caret"></span></a>
              <ul class="dropdown-menu megamenu row fadeIn animated">
                <li class="col-md-4 col-sm-2">
                  <ul class="mega-list">
                    <li class="@if(Request::is('profil/sejarah')) active @endif"> <a class="text-bold" href="{{ route('profil.sejarah') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Sejarah</a></li>
                    <li class="@if(Request::is('profil/letak-geografis')) active @endif"><a href="{{ route('profil.letak-geografis') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Letak Geografis</a></li>
                    <li class="@if(Request::is('profil/struktur-pemerintahan')) active @endif"><a href="{{ route('profil.struktur-pemerintahan') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Struktur Pemerintahan</a></li>
                    <li class="@if(Request::is('profil/visi-dan-misi')) active @endif"><a href="{{ route('profil.visi-misi') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Visi & Misi</a></li>
                  </ul>
                </li>
                <li class="col-md-4 col-sm-4">
                  <h4 class="text-bold text-center">Sambutan {{ $sebutan_kepala_wilayah }} {{ $profil->nama_kecamatan }}</h4>
                  <small class="" style="text-align:justify;">{{ strip_tags($profil->sambutan) }} </small>
                </li>
                <li class="col-md-4 col-sm-3 text-center">
                  <img src="@if(isset($profil->foto_kepala_wilayah)) {{ asset($profil->foto_kepala_wilayah) }} @else {{ asset('img/no-profile.png') }} @endif" width="200px" class="img-user">
                  <h6 class="text-bold no-padding">{{ $profil->nama_camat }}</h6>
                  <h6>{{ $sebutan_kepala_wilayah }} {{ $profil->nama_kecamatan }} </h6>
                </li>
                <li style="margin-left:-50px" class="col-sm-3"></li>
              </ul>
          </li>
            <li class="dropdown @if(Request::is('desa/*'))active @endif">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">DESA <span class="caret"></span></a>
                <ul class="dropdown-menu fadeIn animated" style="overflow-y : none" role="menu">
                  @foreach ($navdesa->chunk(2) as $desa)
                    @foreach ($desa as $d)
                    <li><a href="{{ route('desa.show', ['slug' => str_slug(strtolower($d->nama))]) }}">{{ 'Desa ' .ucfirst($d->nama) }}</a></li>
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
          <li><a href="{{ route('faq')}}">FAQ</a></li>
          @if (auth()->guest())
            <li><a href="{{ route('login') }}">LOGIN<span class="sr-only">(current)</span></a></li>
          @else
          <li><a href="{{ route('dashboard')}}">ADMIN</a></li>
          <li><a id="loggout" href="{{ route('logout') }}">LOGOUT<span class="sr-only">(current)</span></a></li>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
          @endif
        </ul>
      </div>
    </nav>
  </header>
  @push('scripts')
<script type="text/javascript">
  $(function () {
    $('#loggout').click(function (e) { 
      e.preventDefault();
      $('#logout-form').submit();  
    });
  });
</script>
  @endpush