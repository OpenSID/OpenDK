@extends('layouts.app')
@section('title','Struktur Pemerintahan') 
@section('content')
<div class="col-md-8">
    <div class="box box-warning">
        <div class="box-header with-border">
            <div class="box-header with-border text-bold">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> STRUKTUR PEMERINTAHAN  {{ strtoupper($sebutan_wilayah) }} {{ strtoupper($profil->nama_kecamatan) }}</h3>
            </div>
        </div>
        <div class="box-body">
            <!-- The Modal -->
            <img id="myImg"  style="width:97%;" src="{{ is_img($profil->file_struktur_organisasi) }}">
            <!-- The Modal -->
            @foreach ($pengurus as $item)
                <div class="col-md-5 col-xs-8">
                    <label for="">{{ $item->jabatan->nama }}</label> 
                </div>
                <label for="" class="col-md-1 no-padding">: </label>
                <div class="col-md-6">
                    <label for="" class="text-muted">{{ $item->namaGelar }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection



