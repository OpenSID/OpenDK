<div class="col-md-3 col-lg-3">
    <div class="box box-info">
        <div class="box-body no-padding">
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li {{ Request::is('informasi/media-sosial') ? 'class=active' : '' }}><a href="{{ route('informasi.media-sosial.index') }}">Facebook</a></li>
                </ul>
                <ul class="nav nav-stacked">
                    <li {{ Request::is('informasi/media-sosial/twitter') ? 'class=active' : '' }}><a href="{{ route('informasi.media-sosial.twitter') }}">Twitter</a></li>
                </ul>
                <ul class="nav nav-stacked">
                    <li {{ Request::is('informasi/media-sosial/youtube') ? 'class=active' : '' }}><a href="{{ route('informasi.media-sosial.youtube') }}">Youtube</a></li>
                </ul>
                <ul class="nav nav-stacked">
                    <li {{ Request::is('informasi/media-sosial/instagram') ? 'class=active' : '' }}><a href="{{ route('informasi.media-sosial.instagram') }}">Instagram</a></li>
                </ul>
                <ul class="nav nav-stacked">
                    <li {{ Request::is('informasi/media-sosial/whatsapp') ? 'class=active' : '' }}><a href="{{ route('informasi.media-sosial.whatsapp') }}">Whatsapp</a></li>
                </ul>
                <ul class="nav nav-stacked">
                    <li {{ Request::is('informasi/media-sosial/telegram') ? 'class=active' : '' }}><a href="{{ route('informasi.media-sosial.telegram') }}">Telegram</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
