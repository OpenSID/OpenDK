@props(['galeri', 'variant' => 'thumb'])

@use(App\Enums\TipeMedia)

@php
    $type = $galeri->media_type;
    $url = $galeri->media_url;
    $embed = $galeri->media_embed_url;
    $poster = $galeri->gambar_path ?: asset('/img/no-image.png');
    $isFull = $variant === 'full';
    $isYoutube = $type === TipeMedia::Youtube;
    $isVideo = in_array($type, [TipeMedia::Video, TipeMedia::Youtube], true);
@endphp

<div class="galeri-media" data-media-type="{{ $type }}">
    @if ($isFull && $isYoutube && $embed)
        {{-- Embed YouTube memakai iframe karena YouTube tidak mengizinkan pemutaran langsung. --}}
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="{{ $embed }}" title="{{ $galeri->judul }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
        </div>
    @elseif ($isFull && $type === TipeMedia::Video && $embed)
        {{-- Google Drive/Vimeo tidak mendukung Range Request, jadi pakai preview resmi. --}}
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="{{ $embed }}" title="{{ $galeri->judul }}" allow="autoplay" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
        </div>
    @elseif ($isFull && $type === TipeMedia::Video && $url)
        <video src="{{ $url }}" controls preload="metadata" style="width:100%;max-height:70vh;background:#000;border-radius:4px;">
            Browser Anda tidak mendukung pemutaran video. <a href="{{ $url }}">Unduh video</a>.
        </video>
    @elseif ($type === TipeMedia::Image && $url)
        <img src="{{ $url }}" alt="{{ $galeri->judul }}" loading="lazy" style="{{ $isFull ? 'width:100%;height:auto;border-radius:4px;' : 'max-height:60px;max-width:90px;object-fit:contain;border-radius:3px;' }}" onerror="this.src='{{ asset('/img/no-image.png') }}'">
    @else
        <img src="{{ $poster }}" alt="{{ $galeri->judul }}" loading="lazy" style="{{ $isFull ? 'width:100%;height:auto;border-radius:4px;' : 'max-height:60px;max-width:90px;object-fit:contain;border-radius:3px;' }}" onerror="this.src='{{ asset('/img/no-image.png') }}'">
    @endif

    @if ($isVideo && !$isFull)
        <span class="label label-primary galeri-media__badge">
            <i class="fa {{ $isYoutube ? 'fa-youtube-play' : 'fa-play-circle' }}"></i>
            {{ $isYoutube ? 'YouTube' : 'Video' }}
        </span>
    @endif
</div>
