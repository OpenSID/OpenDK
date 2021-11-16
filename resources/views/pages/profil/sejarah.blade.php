@extends('layouts.app')
@section('title','Sejarah')
@section('content')
<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-header with-border text-bold">
            <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> SEJARAH {{ strtoupper($sebutan_wilayah) }} {{ strtoupper($profil->kecamatan->nama) }}</h3>
        </div>
        <div class="box-body">

            <img class="img-circle" style="display:block;margin:auto" src="{{ is_logo($profil_wilayah->file_logo) }}">
            <p> {!! $profil->dataumum->tipologi !!}</p>
        </div>
    </div>
</div>
@endsection



