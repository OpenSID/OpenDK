@extends('layouts.app')
@section('title', 'Akas')
@section('content')
<div class="col-md-8">
	<div class="box">
		<img class="img-responsive" src="{{ url($artikel->gambar) }}" alt="{{ $artikel->slug }}">
		<br/>
		<h4 style="margin-top: 5px; text-align: justify;"><b>{{ $artikel->judul }}</b></h4>
		<p><i class="fa fa-calendar"></i>&ensp;{{ $artikel->created_at->format('d M Y') }}&ensp;|&ensp;<i class="fa fa-user"></i>&ensp;Admin</p>
		<p>{!! $artikel->isi !!}</p>
	</div>	
</div>
@endsection