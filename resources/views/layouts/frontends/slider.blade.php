@if (Route::currentRouteName() === 'beranda')
<!-- Slider -->
<div id="slider">
    <div class="slides">
        @php $slides  = \App\Models\Slide::orderBy('created_at','DESC')->take(4)->get(); @endphp
        @forelse ($slides as $slide)
        
        <div class="slider-class">
            <div class="legend"></div>
            <div class="content-slide">
                <div class="content-txt">
                    <h1>{{ $slide->judul }}</h1>
                    <h2>{{ $slide->deskripsi }}</h2>
                </div>
            </div>
            <div class="image">
                <img src="{{ asset($slide->gambar) }}">
            </div>
        </div>
        @empty          
        <div class="slider-class">
            <div class="legend"></div>
            <div class="content-slide">
                <div class="content-txt">
                    <h1>Pantai Garassikang</h1>
                    <h2>Lokasi: Bulu Jaya, Kecamatan Bangkala Barat, Kabupaten Jeneponto, Sulawesi Selatan</h2>
                </div>
            </div>
            <div class="image">
                <img src="{{ asset('/slide/slide-1.png') }}">
            </div>
        </div>
        <div class="slider-class">
            <div class="legend"></div>
            <div class="content-slide">
                <div class="content-txt">
                    <h1>Batu Siping</h1>
                    <h2>Lokasi: Karampuang, Desa Garassikang, Kecamatan Bangkala Barat, Kabupaten Jeneponto, Sulawesi Selatan.</h2>
                </div>
            </div>
            <div class="image">
                <img src="{{ asset('/slide/slide-2.png') }}">
            </div>
        </div>
        <div class="slider-class">
            <div class="legend"></div>
            <div class="content-slide">
                <div class="content-txt">
                    <h1>Bukit Sinalu Bulu Jaya</h1>
                    <h2>Lokasi: Bulu Jaya, Kecamatan Bangkala Barat, Kabupaten Jeneponto, Sulawesi Selatan</h2>
                </div>
            </div>
            <div class="image">
                <img src="{{ asset('/slide/slide-4.png') }}">
            </div>
        </div>
        <div class="slider-class">
            <div class="legend"></div>
            <div class="content-slide">
                <div class="content-txt">
                    <h1>Pantai Tamarunang</h1>
                    <h2>Lokasi: Tamarunang, Pabiringa, Kecamatan Binamu, Kabupaten Jeneponto, Sulawesi Selatan</h2>
                </div>
            </div>
            <div class="image">
                <img src="{{ asset('/slide/slide-3.png') }}">
            </div>
        </div>
        @endforelse
        

    </div>
    <div class="switch">
        <ul>
            <li>
                <div class="on"></div>
            </li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</div>
<div class="dk-line">
</div>
@endif