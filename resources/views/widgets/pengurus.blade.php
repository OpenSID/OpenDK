<div id="swiper-pengurus" class="swiper">
    <div class="swiper-wrapper">
        @foreach ($pengurus as $item)
            <div class="swiper-slide">
                <div class="pad text-bold bg-white" style="text-align:center;">
                    <img src="{{ is_user($item->foto, $item->sex, true) }}" width="auto" height="400px" class="img-user" style="max-height: 256px; object-fit: contain;  width: 250px;">

                </div>
                <div class="box-header text-center  with-border bg-blue">
                    <h2 class="box-title text-bold" data-toggle="tooltip" data-placement="top">
                        {{ $item->namaGelar }} <br /> <span style="font-size: 14px;color: #ecf0f5;"> {{ $item->jabatan->nama }} </span></h6>
                    </h2>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Add Arrows -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            var swiper = new Swiper("#swiper-pengurus", {
                autoplay: {
                    delay: 3000,
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
