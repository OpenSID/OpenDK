@extends('layouts.app')
@push('scripts')
<style>
	.isi-artikel {
		padding: 10px;
	}
</style>
@endpush
@section('content')
<div class="col-md-8">
	<div class="post">
		<img class="img-responsive" src="{{ is_img($artikel->gambar) }}" alt="{{ $artikel->slug }}">

		<div class="isi-artikel">
			<h3 style="margin-top: 5px; text-align: justify;"><b>{{ $artikel->judul }}</b></h3>
			<p><i class="fa fa-calendar"></i>&ensp;{{ $artikel->created_at->format('d M Y') }}&ensp;|&ensp;<i class="fa fa-user"></i>&ensp;Administrator</p>
			<p>{!! $artikel->isi !!}</p>
			<hr/>
			<div style="margin-top:-10px" class="sharethis-inline-share-buttons"></div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=61eeb3e9e14521001ac13912&product=inline-share-buttons" async="async"></script>
@endpush