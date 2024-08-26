@if (Route::currentRouteName() === 'beranda' && $slides->count() > 0)
    <!-- Slider -->
    <div id="swiper-slider" class="swiper">
        <div class="swiper-wrapper">
            @foreach ($slides as $slide)
                <div class="swiper-slide">
                    <div class="slider-class">
                        <div class="legend"></div>
                        <div class="content-slide">
                            <div class="content-txt">
                                <h1>{{ $slide->judul }}</h1>
                                <h2>{{ $slide->deskripsi }}</h2>
                            </div>
                        </div>
                        <div class="image">
                            <img src="{{ Str::contains($slide->gambar, 'storage') ? asset($slide->gambar) : $slide->gambar }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>
    <div class="dk-line"></div>
@endif

@push('scripts')
    <script>
        $(document).ready(function() {
            var swiper = new Swiper("#swiper-slider", {
                autoplay: {
                    delay: 4000,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
@endpush
