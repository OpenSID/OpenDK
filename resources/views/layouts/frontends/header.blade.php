<header id="navbar"  class="main-header">
    <nav class="navbar  navbar-static-top">
      <div class="container">
        <div class="navbar-header">
            <a href="{{ route('beranda')}}"  class="navbar-brand"><img  src="{{ asset("/img/logo-coba.png")}}" alt="KD" id="logo-brand" width="100px"></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse js-navbar-collapse" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown @if(Request::is('/'))active @endif"><a href="{{ route('beranda') }}">BERANDA <span class="sr-only">(current)</span></a></li>
            <li class="dropdown menu-large">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"> PROFIL <span class="caret"></span></a>
              <ul class="dropdown-menu megamenu row fadeIn animated">
                <li class="col-md-4 col-sm-2">
                  <ul class="mega-list">
                    <li class="@if(Request::is('profil/sejarah-*'))active @endif"> <a class="text-bold" href="{{ route('profil.sejarah', ['wilayah' => (strtolower($nama_wilayah))] ) }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Sejarah</a></li>
                    <li class="@if(Request::is('profil/letak-geografis'))active @endif"><a href="{{ route('profil.letak-geografis') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Letak Geografis</a></li>
                    <li class="@if(Request::is('profil/struktur-pemerintahan'))active @endif"><a href="{{ route('profil.struktur-pemerintahan') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Struktur Pemerintahan</a></li>
                    <li class="@if(Request::is('profil/visi-dan-misi'))active @endif"><a href="{{ route('profil.visi-misi') }}"><i class="fa  fa-arrow-circle-right text-blue"></i>  Visi & Misi</a></li>
                  </ul>
                </li>
                <li class="col-md-4 col-sm-4">
                  <h4 class="text-bold text-center">Sambutan Kepala {{ $sebutan_wilayah }} {{ $nama_wilayah }}</h4> Alhamdulillahhirobbilalamin
                  <p style="text-align:justify;font-size:12px;"> Dengan mengucapkan puji dan syukur kehadirat Allah SWT, kami sampaikan salam hangat bagi warga {{ $sebutan_wilayah }} {{ $nama_wilayah }}. Selanjutnya dengan senang hati kami sampaikan informasi kepada Anda untuk mengenal lebih dekat {{ $sebutan_wilayah }} {{ $nama_wilayah }} melalui situs resmi {{ $sebutan_wilayah }} {{ $nama_wilayah }} ini. Situs ini diharapkan akan memberikan informasi mengenai {{ $sebutan_wilayah }} {{ $nama_wilayah }} secara umum tentang pemerintahan, pembangunan dan kemasyarakatan, termasuk didalamnya, khasanah budaya, potensi ekonomi dan pariwisata, yang selalanjutnya dapat digunakan dinas instansi terkait dan stakeholder lainnya dalam rangka pelaksanaan program-progam pembangunan di {{ $sebutan_wilayah }} {{ $nama_wilayah }} untuk meningkatkan kesejahteraan masyarakat. </p>
                </li>
                <li class="col-md-4 col-sm-3 text-center">
                  <img src="{{ asset('/uploads/user/user1-128x128.jpg') }}" width="200px" class="img-user">
                  <h6 class="text-bold no-padding">{{ $profil_wilayah->nama_camat }}</h6>
                  <h6>Kepala {{ $sebutan_wilayah }} {{ $nama_wilayah }} </h6>
                </li>
                <li style="margin-left:-50px" class="col-sm-3"></li>
              </ul>
          </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">PUBLIKASI <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                </ul>
            </li>
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
          <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
              <input type="text" class="form-control" name="mencari" id="navbar-search-input" placeholder="Search">
            </div>
          </form> 
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        {{-- <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            @if (Sentinel::guest())
            <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
              <a href="{{ route('login') }}" class="dropdown-toggle">
                <img src="{{ asset('/img/login.png')}}" class="user-image" alt="User Image">
                <span class="hidden-xs">{{ __('Login') }}</span>
            </a>
              @else
                  @php
                      $user = Sentinel::getUser();
                  @endphp  
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ asset("/img/login.png")}}" class="user-image" alt="User Image">
                <span class="hidden-xs">{{ $user->first_name}}</span>
                </a>
              <ul class="dropdown-menu">  
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="{{ asset("/img/login.png")}}" class="img-circle" alt="User Image">
                  <p>
                    {{ $user->first_name .' '.$user->last_name }}
                    <small>Member since {{ date('M, Y', strtotime($user->created_at)) }}</small>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                  </div>
                  <div class="pull-right">
                    <a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="">
                     {{ __('Logout') }}
                 </a>
                 <form id="logout-form" action="{{ route('logout') }}" method="POST"
                       style="display: none;">
                       @csrf
                 </form>
                  </div>
                </li>
              </ul>
               @endif
            </li>
          </ul>
        </div> --}}
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>