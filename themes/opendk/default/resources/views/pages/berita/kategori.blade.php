@extends('layouts.app')
@section('title', 'Berita Kategori :'. $kategori->nama)
@push('css')
<link rel="stylesheet" href="{{ asset('/css/desa.css') }}">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
@endpush
@section('content')
<div class="col-md-8">

    <!-- Berita Kecamatan -->
    <div class="fat-arrow">
        <div class="flo-arrow"><i class="fa fa-globe fa-lg fa-spin"></i></div>
    </div>
    <div class="page-header" style="margin:0px 0px;">
        <span style="display: inline-flex; vertical-align: middle;"><strong class="">Berita Kecamatan Kategori : {{ $kategori->nama }}</strong></span>
    </div>
    @include('pages.berita.index')

</div>
@endsection

