<div class="topheader">
    <div class="container">
        <div id="particles-js"></div>
        <div class="col-md-6 col-sm-6 no-padding">
            <div class="navbar-left">
                <h5 class="">Selamat Datang di Website {{ $sebutan_wilayah  }} {{ $nama_wilayah }}</h5>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 no-padding">
            <div class="navbar-right">
                <ul class="social-icon list-inline m-0">
                    @foreach(json_decode($profil_wilayah->socialmedia) as $sosmed)
                    <li><a target="_BLANK" href="{{ $sosmed->link }}" class="site-button-link facebook hover"><i
                                class="{{ $sosmed->icon }}"></i></a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
