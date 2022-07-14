<div class="col-md-3 col-lg-3">
        <div class="box box-info">
            <div class="box-body no-padding">
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li class="@active($navigasi === 'facebook')"><a href="{{ route('informasi.media-sosial.index') }}">Facebook</a></li>
                    </ul>
                    <ul class="nav nav-stacked">
                        <li class="@active($navigasi === 'twitter')"><a href="{{ route('informasi.media-sosial.twitter') }}">Twitter</a></li>
                    </ul>
                    <ul class="nav nav-stacked">
                        <li class="@active($navigasi === 'youtube')"><a href="{{ route('informasi.media-sosial.youtube') }}">Youtube</a></li>
                    </ul>
                    <ul class="nav nav-stacked">
                        <li class="@active($navigasi === 'instagram')"><a href="{{ route('informasi.media-sosial.instagram') }}">Instagram</a></li>
                    </ul>
                    <ul class="nav nav-stacked">
                        <li class="@active($navigasi === 'whatsapp')"><a href="{{ route('informasi.media-sosial.whatsapp') }}">Whatsapp</a></li>
                    </ul>
                    <ul class="nav nav-stacked">
                        <li class="@active($navigasi === 'telegram')"><a href="{{ route('informasi.media-sosial.telegram') }}">Telegram</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>