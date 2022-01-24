@extends('layouts.app')
@section('title', 'Akas')
@section('content')
<div class="col-md-8">
	<div class="box">
		<div class="box-body">
			<h4 style="margin-top: 5px;"><b>{{ $artikel->judul }}</b></h4>
			<p class="text-warning"><i class="fa fa-calendar"></i>&ensp;{{ $artikel->created_at->translatedFormat('d F Y') }}&ensp;|&ensp;<i class="fa fa-user"></i>&ensp;Administrator | <i class="fa fa-comments"></i> {{ $artikel->comments->count() }} </p>
			<img class="img-responsive" width="100%" src="{{ is_img($artikel->gambar) }}" alt="{{ $artikel->slug }}">
			<br/>
			<div class="sharethis-inline-share-buttons"></div>
			<p>{!! $artikel->isi !!}</p>
		</div>
	</div>
	<div style="margin-top:-20px" class="text-small sharethis-inline-reaction-buttons"></div>	
	<div style="margin-top:-10px" class="sharethis-inline-share-buttons"></div>
	<h4>Komentar Terlama</h4> <div class="sharethis-inline-follow-buttons"></div>
	<br>
		@include('pages.berita._komentar', ['comments' => $artikel->comments, 'artikel_id' => $artikel->id])
		<hr />
		
		<div class="box box-widget">
			<div class="komentar">
			<h4>Komentar</h4>
			<form method="post" action="{{ route('komentar.store'   ) }}">
				@csrf
				<div class="form-group">
					<textarea class="form-control" name="komentar"></textarea>
					<input type="hidden" name="artikel_id" value="{{ $artikel->id }}" />
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-share"></i> Kirim</button>
				</div>
				<small class="text-danger">***Komentar yang sopan tanpa unsur SARA</small>
			</form>
			</div>
		</div>
	<h4>BERITA LAINNYA</h4>
	<div class="row">
		@forelse($OtherArtikel as $artikel)
		<div class="col-md-6">
			<div class="box box-widget">
					<div class="artikel-img" style="overflow:hidden;">
					<img class="hover img-responsive" width="100%" src="{{ is_img($artikel->gambar) }}" alt="{{ $artikel->slug }}">
					</div>
					<div class="box-footer">
					<div style="padding:10px;">
						<small class="text-yellow">{{ $artikel->created_at->translatedformat('l') }}, {{ $artikel->created_at->translatedFormat('d F Y') }}</small>
						<a href="{{ $artikel->slug }}">
							<h5>
								{{ strtoupper($artikel->judul) }}
							</h5>
						</a>
					</div>
				</div>
			</div>
		</div>
		@empty
		@endforelse
	</div>
</div>
@endsection