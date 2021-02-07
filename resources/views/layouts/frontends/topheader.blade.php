<div class="topheader">
    <div class="container">
        <div class="col-md-6 col-sm-6 no-padding">
            <div class="navbar-left">
                <h5>Selamat Datang di Website {{ $sebutan_wilayah  }} {{ $nama_wilayah }}</h5>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 no-padding">
            <div class="navbar-right">
                <ul class="social-icon list-inline m-0">
                    @if (isset($profil_wilayah->socialmedia))
                    @foreach(json_decode($profil_wilayah->socialmedia) as $sosmed)
                    <li><a target="_BLANK" href="{{ $sosmed->link ?? ''}}" class="site-button-link facebook hover"><i
                                class="{{ $sosmed->icon ?? '' }}"></i></a></li>
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
