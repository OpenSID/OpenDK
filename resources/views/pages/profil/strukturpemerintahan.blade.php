@extends('layouts.app')
@section('title','Struktur Pemerintahan') 
@section('content')
<div class="col-md-8">
    <div class="box box-warning">
        <div class="box-header with-border">
            <div class="box-header with-border text-bold">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> STRUKTUR PEMERINTAHAN  {{ strtoupper($sebutan_wilayah) }} {{ strtoupper($profil->kecamatan->nama) }}</h3>
            </div>
        </div>
        <div class="box-body">
            <!-- The Modal -->
            <img id="myImg"  style="width:97%;" src="@if(! $profil->file_struktur_organisasi =='') {{ asset($profil->file_struktur_organisasi) }} @else {{ 'http://placehold.it/700x400' }} @endif">
            <!-- The Modal -->
            <div id="myModal" class="modal">
                <!-- The Close Button -->
                <span class="close">&times;</span>
                <!-- Modal Content (The Image) -->
                <img class="modal-content" id="img01">
                <!-- Modal Caption (Image Text) -->
                <div id="caption"></div>
            </div>
        </div>
    </div>
</div>
@endsection



