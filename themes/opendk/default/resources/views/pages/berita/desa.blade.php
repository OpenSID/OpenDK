@extends('layouts.app')
@push('css')
<link rel="stylesheet" href="{{ asset('/css/desa.css') }}">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">

@endpush
@section('content')
<div class="col-md-8">

    <!-- Berita Desa -->
    <div class="fat-arrow">
        <div class="flo-arrow"><i class="fa fa-globe fa-lg fa-spin"></i></div>
    </div>
    <form class="form-horizontal" id="form_filter" method="get" action="{{ route('filter-berita-desa') }}">
        <input type="hidden" value="1" name="page">
        <div class="page-header" style="margin:0px 0px;">
            <strong>Berita
                        {{ config('setting.sebutan_desa') }}</strong>
        </div>
        <div class="page-header"  style="margin:0px 0px; padding: 0px;">
            <div class="row page-header-row">
                <div class="col-md-8 page-header-left">                    
                    @include('layouts.fragments.select-desa')
                    <select class="form-control select2" id="tanggal" name="tanggal">
                        <option value="Terlama">Terbaru</option>
                        <option value="Terbaru">Terlama</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm" style="display: inline-flex; float: right; padding: 5px;">
                        <input class="form-control" type="text" name="cari" placeholder="Cari berita" value="{{ $cari }}" style="height: auto;" />
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('pages.berita.feeds')

</div>
@endsection