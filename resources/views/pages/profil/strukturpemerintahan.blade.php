@extends('layouts.app')
@section('title', 'Struktur Pemerintahan')
@section('content')
    <div class="col-md-8">
        <div class="box box-warning">
            <div class="box-header with-border">
                <div class="box-header with-border text-bold">
                    <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> STRUKTUR PEMERINTAHAN {{ strtoupper($sebutan_wilayah) }} {{ strtoupper($profil->nama_kecamatan) }}</h3>
                </div>
            </div>
            <div class="box-body">
                <!-- The Modal -->
                <img id="myImg" style="width:97%;" src="{{ is_img($profil->file_struktur_organisasi) }}">
                <!-- The Modal -->
                @foreach ($pengurus as $item)
                    <div class="col-md-3 col-sm-6">
                        <div class="pad text-bold bg-white" style="text-align:center;">
                            <img id="myImg" src="{{ is_user($item->foto, $item->sex, true) }}" width="auto" height="120px" class="img-user" style="object-fit: contain;">

                        </div>
                        <div class="box-header text-center  with-border bg-blue">
                            <p class="box-title text-bold text-small" data-toggle="tooltip" data-placement="top" style="font-size: 12px;">
                                {{ $item->namaGelar }} <br /> <span style="font-size: 10px;color: #ecf0f5;"> {{ $item->jabatan->nama }} </span></h6>
                            </p>
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
