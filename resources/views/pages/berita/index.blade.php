<div id="kecamatan">
  <div class="post clearfix">
    @forelse ($artikel as $item)
    <div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">
      <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="artikel-img" style="overflow:hidden;">
          <img class="hover img-responsive" src="{{ is_img($item->gambar) }}">
            <div class="artikel-img-items">
                <h5 style="text-align:center;"><a href="berita/{{ $item->slug }}">{{ strtoupper($item->judul) }}</a></h5>
            </div>
          <div class="artikel-img-button text-center">
            <button class="btn btn-danger flat btn-xs">Selengkapnya</button>
        </div>
		</div>
  </div>
      <div class="col-md-8 col-sm-6">
          <p style="font-size:8pt;" class="text-yellow" >{{ $item->created_at->translatedFormat('d F Y') }} | Administrator | | <i class="fa fa-comments-o"></i> {{ $item->comments->count() }}</p>
          <h5 style="margin-top: 5px; text-align: justify;" class="text-bold"><a href="berita/{{ $item->slug }}" class="text-black">{{ strtoupper($item->judul) }}</a></h5>
          <p style="text-align: justify;">{{ strip_tags(substr($item->isi, 0, 350)) }}... <a href="berita/{{ $item->slug }}" class="" target="_blank">Selengkapnya</a> </p>
      </div> 
  </div>
</div>
    @empty
      <div class="callout callout-info">
        <p class="text-bold">Tidak ada berita kecamatan yang ditampilkan!</p>
      </div>
    @endforelse
    <div class="text-center">
      {{ $artikel->links() }}
    </div>
  </div>
</div>