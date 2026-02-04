<section class="footer-top">
    <div class="container no-padding">
        <div class="col-md-2">
            <div id="social-media-container">
                <h5 class="text-bold">IKUTI KAMI</h5>
                <ul class="social-icon list-inline m-0">
                    <li><i class="fa fa-spinner fa-spin"></i> Loading...</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <h5 class="text-bold">{{ config('setting.sebutan_desa') }}</h5>
            <div id="desa-list-container">
                <ul class="no-padding">
                    <li><i class="fa fa-spinner fa-spin"></i> Loading...</li>
                </ul>
            </div>
        </div>
        <div class="col-md-4 col-xs-12" id="desa-profil-container">
            <i class="fa fa-spinner">Loading ...</i>
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
        <strong>Hak Cipta &copy; 2017 <a href="http://www.kompak.or.id">KOMPAK</a>, 2018-{{ date('Y') }} <a
                href="http://opendesa.id">OpenDesa</a> <i class="fa fa-github"></i></strong> Hak cipta dilindungi.
    </div>
</footer>
