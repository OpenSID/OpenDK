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
<<<<<<< HEAD
                    @foreach(json_decode($profil_wilayah->socialmedia) as $sosmed)
                    <li><a target="_BLANK" href="{{ $sosmed->link }}" class="site-button-link facebook hover"><i
                                class="{{ $sosmed->icon }}"></i></a></li>
                    @endforeach
=======
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
>>>>>>> 2890337063ab134daf3e7f211cd0f029924addf1
                </ul>
            </div>
        </div>
    </div>
</div>
