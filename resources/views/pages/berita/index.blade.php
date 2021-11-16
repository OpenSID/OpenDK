<div class="fat-arrow">
	<div class="flo-arrow"><i class="fa fa-globe fa-lg fa-spin"></i></div>
</div>
<div class="page-header" style="margin:0px 0px;">
  <span style="display: inline-flex; vertical-align: middle;"><strong class="">Berita Kecamatan</strong></span>
</div>

<div id="kecamatan">
  <div class="post clearfix">
    @forelse ($artikel as $item)
    <div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">
      <div class="row">
        <div class="col-sm-4">
          <img class="img-responsive" src="{{ is_img($item->gambar) }}" alt="{{ $item->slug }}">
        </div>
        <div class="col-sm-8">
          <h5 style="margin-top: 5px; text-align: justify;"><b><a href="berita/{{ $item->slug }}">{{ $item->judul }}</a></b></h5>
          <p style="text-align: justify;">{{ strip_tags(substr($item->isi, 0, 250)) }}...</p>
          <a href="berita/{{ $item->slug }}" class="btn btn-sm btn-primary" target="_blank">Selengkapnya</a>
        </div>
      </div>
    </div>
    @empty
      <div class="callout callout-info">
        <p class="text-bold">Tidak ada berita kecamatan yang ditampilkan!</p>
      </div>
    @endforelse
    {{ $artikel->links() }}
  </div>
</div>