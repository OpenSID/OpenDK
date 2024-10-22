<div id="kecamatan">
    <div class="post clearfix">
        @forelse ($artikel as $item)
            <div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">
                <div class="row">
                    <div class="col-sm-4">
                        <img class="img-responsive" src="{{ is_img($item->gambar) }}" alt="{{ $item->slug }}">
                    </div>
                    <div class="col-sm-8">
                        <h5 style="margin-top: 5px; text-align: justify;"><b><a href="{{ route('berita.detail', $item->slug) }}">{{ $item->judul }}</a></b></h5>
                        <p style="font-size:11px;">
                            <i class="fa fa-calendar"></i>&ensp;{{ format_date($item->created_at) }}&ensp;|&ensp;
                            <i class="fa fa-user"></i>&ensp;Administrator&ensp;
                            @if ($item->kategori)
                            |&ensp;<i class="fa fa-tag"></i>&ensp;<a href="{{ route('berita-kategori', ['slug' => $item->kategori->slug]) }}">{{ $item->kategori->nama_kategori }}</a>
                            @endif
                        </p>
                        <p style="text-align: justify;">{{ strip_tags(substr($item->isi, 0, 250)) }}...</p>
                        <a href="{{ route('berita.detail', $item->slug) }}" class="btn btn-sm btn-primary" target="_blank">Selengkapnya</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="callout callout-info">
                <p class="text-bold">Tidak ada berita kecamatan yang ditampilkan!</p>
            </div>
        @endforelse
        <div class="text-center">
            {{ $artikel->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
</div>
