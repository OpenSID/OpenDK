<section class="footer-top">
    <div class="container no-padding">
      <div class="col-md-2">
        @if (isset($profil->socialmedia))
        <h5 class="text-bold">IKUTI KAMI</h5>
        <ul class="social-icon list-inline m-0">
          @foreach(json_decode($profil->socialmedia) as $sosmed)
          <li><a target="_BLANK" href="{{ $sosmed->link ?? '' }}" class="site-button-link facebook hover"><i
                      class="{{ $sosmed->icon ?? ''}}"></i></a></li>
          @endforeach
        </ul>
        @endif
    </div>
      <div class="col-md-6">
        <h5 class="text-bold">Desa dan kelurahan</h5>
        @foreach ($navdesa->chunk(2) as $desa)
          @foreach ($desa as $d)
          <ul class="no-padding">
              <li class="col-12 col-xs-6 no-padding"><a class="footer-link" href="{{ route('desa.show', ['slug' => str_slug(strtolower($d->nama))]) }}"><i class="fa  fa-chevron-circle-right"></i> {{ ucwords($d->sebutan_desa . ' ' . $d->nama) }}</a></li>
          </ul>
          @endforeach  
        @endforeach
      </div>
      <div class="col-md-4 col-xs-12">
        <h5 class="text-bold">Kantor {{ $sebutan_wilayah }} {{ $profil->nama_kecamatan }}</h5>
        <ul class="no-padding">
          <li> <small style="text-indent: 0px; font-size:15px"><i class="fa fa-map-marker"></i> {{ $profil->alamat }}</small></li>
          <li><small style="text-indent: 0px ;font-size:15px"><i class="fa fa-fax"></i> {{ $profil->kode_pos }}</small></li>
          <li><small style="text-indent: 0px ;font-size:15px"><a href="http://mailto:{{ $profil->email }}" target="_blank"><i class="fa fa-envelope"></i> {{ $profil->email }}</a></small></li>
          <li><small style="text-indent: 0px ;font-size:15px"><i class="fa fa-phone"></i> {{ $profil->telepon }}</small></li>
        </ul>
      </div>
    </div>
  </section>
  <footer class="main-footer footer-bg">
    <div class="container">
      <hr style="border-top: 0.05em solid rgba(0, 0, 0, 0.1);margin-top: 1rem;
      margin-bottom: 1rem;">
      <div class="pull-right hidden-xs no-padding">
        <ul class="navbottom">
          {{-- <li class="listNavbottom"> <strong> <a href="https://github.com/opeSID/openDK" target="_blank"><i class="fa fa-github"></i> </a></strong></li> --}}
          {{-- <li class="listNavbottom"> <strong> <a href="{{ url('/sitemap') }}" target="_blank"> Sitemap</a> </strong></li> --}}
          {{-- <li class="listNavbottom"> <strong><a href="https://yourdomain.com/kontak" target="_blank"> Kontak </a></strong> </li> --}}
        </ul>
      </div>

      <!-- To the right -->
      <div class="pull-right hidden-xs">
          <b><a href="https://github.com/openSID/openDK" target="_blank">OpenDK</a></b> {{ config('app.version') }}
      </div>
    <!-- Default to the left -->
    <strong>Hak Cipta &copy; 2017 <a href="http://www.kompak.or.id">KOMPAK</a>, 2018-{{ date('Y') }} <a href="http://opendesa.id">OpenDesa</a> <i class="fa fa-github"></i></strong> Hak cipta dilindungi.
  </div>
</footer>