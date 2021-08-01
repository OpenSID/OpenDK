	@forelse ($feeds as $item)
		<div class="card flex-md-row mb-4 box-shadow h-md-250">
			<div class="card-body d-flex flex-column align-items-start">
				<h4 > <a class="text-dark" href="{{ $item['link'] }}" target="_blank">
				<strong class="d-inline-block mb-2  text-primary title-article">{{ strtoupper($item['title']) }}</strong></a>
				</h4>
				<div class="mb-1 text-muted" style="font-size: 10px"> <i class="fa fa-link"></i> {{ ucwords($item['feed_title']) }} | <i class="fa fa-user-circle-o"></i> {{ $item['author']}}  @if(isset($item['date'])) | <i class="fa fa-calendar-o"></i> {!! \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y H:i') !!} @endif</div>
				<hr style="margin-top:0px">
				<div class="divider"></div>
				<img class="card-img-right flex-auto d-none d-lg-block" data-src="holder.js/200x250?theme=thumb" alt="Thumbnail [200x250]" style="width: 100%; height: 100%; display;block;" src="{{ get_tag_image($item['description']) }}" data-holder-rendered="true">
				<p class="card-text mb-auto" style=" text-align: justify;">{{ strip_tags($item['description']) }}</p>
				<a href="{{ $item['link'] }}" target="_blank">Baca Selengkapnya</a>
			</div>
		</div>
		<br>
		@empty
		<div class="text-center">
			<p class="text-bold">Tidak ada berita yang ditampilkan!</p>
		</div>
	@endforelse
	{{ $feeds->links() }}
