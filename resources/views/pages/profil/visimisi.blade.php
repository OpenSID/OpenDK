@extends('layouts.app')
@section('title','Visi dan Misi') 
@section('content')
<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> VISI DAN MISI {{ strtoupper($sebutan_wilayah) }} {{ strtoupper($profil->nama_kecamatan) }}</h3>
        </div>
        <div class="box-body">
            <h3 class="box-title text-bold text-center">VISI</h3>
            <p> {!! $profil->visi !!}</p>
            <hr>
            <h3 class="box-title text-bold text-center">MISI</h3>
            <p> {!! $profil->misi !!}</p>
        </div>
    </div>
</div>
@endsection



