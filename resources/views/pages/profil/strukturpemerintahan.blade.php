<style>
    /* Style the Image Used to Trigger the Modal */
    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }
    
    #myImg:hover {opacity: 0.7;}
    
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
    }
    
    /* Modal Content (Image) */
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }
    
    /* Caption of Modal Image (Image Text) - Same Width as the Image */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #000000;
        font-weight: 200;
        padding: 10px 0;
        height: 150px;
    }
    
    /* Add Animation - Zoom in the Modal */
    .modal-content, #caption {
        animation-name: zoom;
        animation-duration: 0.6s;
    }
    
    @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
    }
    
    /* The Close Button */
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }
    
    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
    
    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px){
        .modal-content {
            width: 100%;
        }
    }
</style>
    
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
            <div class="col-md-3 col-sm-6">
                <div class="pad text-bold bg-white" style="text-align:center;">
                    <img id="myImg" src="{{ is_user($item->foto, $item->sex, true) }}" alt="{{ $item->namaGelar . ' - ' . $item->jabatan->nama }}" width="auto" height="120px" class="img-user" style="object-fit: contain;">
                    
                </div>
                <div class="box-header text-center  with-border bg-blue">
                    <p class="box-title text-bold text-small" data-toggle="tooltip" data-placement="top" style="font-size: 12px;">
                        {{ $item->namaGelar }} <br />  <span style="font-size: 10px;color: #ecf0f5;"> {{ $item->jabatan->nama }} </span></h6>
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