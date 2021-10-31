<div class="fat-arrow">
	<div class="flo-arrow"><i class="fa fa-globe fa-lg fa-spin"></i></div>
</div>
<div class="page-header" style="margin:0px 0px;">
  <span style="display: inline-flex; vertical-align: middle;"><strong class="">Berita Kecamatan</strong></span>
</div>

<div id="kecamatan">
  @forelse ($artikel as $item)
  <div class="card flex-md-row mb-4 box-shadow h-md-250">
    <div class="card-body d-flex flex-column align-items-start">
      <div class="card-horizontal">
        <div class="img-square-wrapper">
          <a href="berita/{{ $item->slug }}">
            <img class="" src="{{ url($item->gambar) }}" alt="Card image cap" style="width: 235px;height: 150px;object-fit: cover;">
          </a>
        </div>
        <div class="card">
          <div class="card-body">
            <a href="berita/{{ $item->slug }}">
              <h4 class="card-title text-black" style="margin-top: -5px;">{{ $item->judul }}</h4>
            </a>
            <p class="card-text mb-auto" style=" text-align: justify;">{{ strip_tags(substr($item->isi, 0, 250)) }}</p>
            <a href="berita/{{ $item->slug }}" target="_blank">Selengkapnya</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>
  @empty
    <div class="callout callout-info">
			<p class="text-bold">Tidak ada berita kecamatan yang ditampilkan!</p>
		</div>
  @endforelse
  {{ $artikel->links() }}
</div>