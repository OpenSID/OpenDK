@if (Route::currentRouteName() === 'beranda')
    <!-- Slider -->
    <div id="swiper-slider" class="swiper">
        <div class="swiper-wrapper" id="slides-container">
            <!-- Slides will be populated by JavaScript from API -->
            <div class="swiper-slide">
                <div class="slider-class">
                    <div class="legend"></div>
                    <div class="content-slide">
                        <div class="content-txt">
                            <h1><i class="fa fa-spinner fa-spin"></i> Loading...</h1>
                        </div>
                    </div>
                    <div class="image">
                        <img src="{{ asset('img/placeholder.jpg') }}" alt="Loading slides...">
                    </div>
                </div>
            </div>
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
