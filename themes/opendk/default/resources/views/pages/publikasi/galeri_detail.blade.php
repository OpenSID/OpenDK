@extends('layouts.app')
@section('title', 'Galeri')

@push('css')
<style>
    .image-container {
        width: 100%;
        padding-top: 100%;
        /* Menyusun rasio aspek 1:1 (tinggi = lebar) */
        position: relative;
        margin-bottom: 15px;
        /* Sesuaikan dengan jarak yang diinginkan */
    }

    .image-container img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Menjaga proporsi gambar dengan crop jika perlu */
    }
</style>
@endpush

@section('content')
<div class="col-md-8">
    <div class="box box-warning">
        <div class="box-header with-border">
            <div class="box-header with-border text-bold">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> Galeri : {{
                    strtoupper($galeri->judul) }}</h3>
            </div>
        </div>
        <div class="box-body">

            <!-- The Modal -->
            @foreach ($galeri->gambar as $item)
            <div class="col-md-6 mt-2">
                <div class="image-container">
                    <img id="myImg" class="img-fluid" src="{{ isThumbnail("publikasi/galeri/{$item}") }}" alt="Image">
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('forms.image-modal')
@endpush