<div class="topheader">
    <div class="container">
        <div class="col-md-8 col-sm-10 col-xs-12 no-padding">
            <div class="navbar-left">
                <h5>Selamat Datang di Website {{ $sebutan_wilayah  }} {{ $profil->nama_kecamatan }}</h5>
            </div>
        </div>
        <div class="col-md-4 col-sm-2 hidden-xs no-padding">
            <div class="navbar-right">
                <ul class="social-icon list-inline m-0">
                    @if (isset($profil->socialmedia))
                    @foreach(json_decode($profil->socialmedia) as $sosmed)
                    <li><a target="_BLANK" href="{{ $sosmed->link ?? ''}}" class="site-button-link facebook hover"><i class="{{ $sosmed->icon ?? '' }}"></i></a></li>
                    @endforeach
                    @else
                    <li></li>
                    </div>
                    @endif 
                </ul>
            </div>
        </div>
    </div>
</div>
