@extends('layouts.app')
@section('title', 'Sambutan')
@section('content')
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> SAMBUTAN
                    {{ strtoupper($sebutan_kepala_wilayah) }} {{ strtoupper($profil->nama_kecamatan) }}</h3>
            </div>
            <div class="box-body">
                <div class="pad text-bold bg-white" style="text-align:center;">
                    <img class="img-circle" style="display:block;margin:auto"
                        src="@if (isset($camat->foto)) {{ asset($camat->foto) }} @else {{ asset('img/no-profile.png') }} @endif">
                    <h4 class="box-title text-bold text-center">{{ $camat->namaGelar }}</h4>
                    <h5 class="box-title text-bold text-center">{{ $sebutan_kepala_wilayah }} {{ $profil->nama_kecamatan }}
                    </h5>
                </div>
                <hr>
                <p> {!! $profil->sambutan !!}</p>
            </div>
        </div>
    </div>
@endsection
