<section class="footer-top">
    <div class="container no-padding">
      <div class="col-md-2">
        <h5 class="text-bold">IKUTI KAMI</h5>
        <ul class="social-icon list-inline m-0">
          {{-- <li><span class="title">Ikuti Kami </span></li> --}}
          <li><a target="_BLANK" href="https://www.facebook.com/" class="site-button-link facebook hover"><i
                      class="fa fa-facebook"></i></a></li>
          <li><a target="_BLANK" href="https://twitter.com/" class="site-button-link facebook hover"><i
                      class="fa fa-twitter"></i></a></li>
          <li><a target="_BLANK" href="https://www.instagram.com/" class="site-button-link facebook hover"><i
                      class="fa fa-instagram"></i></a></li>
          <li><a target="_BLANK" href="https://www.youtube.com/channel/"
                  class="site-button-link facebook hover"><i class="fa fa-youtube"></i></a></li>
          <li><a target="_BLANK" target="_BLANK" href="" class="site-button-link facebook hover"><i
                      class="fa fa-rss"></i></a></li>
      </ul>
    </div>
      <div class="col-md-3">
        <h5 class="text-bold">Desa dan kelurahan</h5>
        <ul class=" no-padding">
          @foreach ($navdesa as $d)  
          <li><a class="footer-link" href="{{ route('desa.show', ['slug' => str_slug(strtolower($d->nama))]) }}"><i class="fa  fa-chevron-circle-right"></i> {{ 'Desa ' .ucfirst($d->nama) }}</a></li>
          @endforeach
          </ul>
      </div>
      <div class="col-md-3">
        <h5 class="text-bold">Agenda Kegiatan</h5>
        <ul class=" no-padding">
          @if(count($events) > 0)
            @foreach($events as $key => $event)
                @foreach($event as $value) 
          <li><a class="footer-link" href="{{ route('event.show', ['slug' => str_slug(strtolower($value->event_name))]) }}"><i class="fa  fa-chevron-circle-right"></i> {{ ucfirst($value->event_name) }}</a></li>
          @endforeach
            @endforeach
        @else
            <li class="time-label">
                <span>
                    Event tidak tersedia.
                </span>
            </li>
        @endif
          </ul>
      </div>
      <div class="col-md-4 col-sm-6">
        <h5 class="text-bold">Kantor {{ $sebutan_wilayah }} {{ $nama_wilayah }}</h5>
        <ul class="no-padding">
          <li> <small style="text-indent: 0px; font-size:15px"><i class="fa fa-map-marker"></i> {{ $profil_wilayah->alamat }}</small></li>
          <li><small style="text-indent: 0px ;font-size:15px"><i class="fa fa-fax"></i> {{ $profil_wilayah->kode_pos }}</small></li>
          <li><small style="text-indent: 0px ;font-size:15px"><a href="http://mailto:{{ $profil_wilayah->email }}" target="_blank"><i class="fa fa-envelope"></i> {{ $profil_wilayah->email }}</a></small></li>
          <li><small style="text-indent: 0px ;font-size:15px"><i class="fa fa-phone"></i> {{ $profil_wilayah->telepon }}</small></li>
        </ul>
      </div>
    </div>
  </section>
  <footer class="main-footer footer-bg">
    <div class="container">
      <div class="divider"></div>
      <hr style="border-top: 0.05em solid rgba(0, 0, 0, 0.1);margin-top: 1rem;
      margin-bottom: 1rem;">
      <div class="pull-right hidden-xs no-padding">
        <ul class="navbottom"> 
          <li class="listNavbottom"> <strong> <a href="https://yourdomain.com/feed" target="_blank"><i class="fa fa-rss"></i> </a></strong></li>
          <li class="listNavbottom"> <strong> <a href="{{ url('/sitemap') }}" target="_blank"> Sitemap</a> </strong></li>
          <li class="listNavbottom"> <strong><a href="https://yourdomain.com/kontak" target="_blank"> Kontak </a></strong> </li>
        </ul>
      </div>
      <strong>&copy; 2017 - {{ now()->format('Y') }} <a href="https://kompak.or.id">KOMPAK.</a> All rights reserved.</strong> 
    </div>
</footer>