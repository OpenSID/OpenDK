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
                <span style="display: inline-flex; vertical-align: middle;"><strong class="">Berita Desa</strong></span>
            </div>
            <div class="page-header" style="margin:0px 0px; padding: 0px;">
                <select class="form-control" id="list_desa" name="desa" style="width: auto;">
                    <option value="Semua">Semua Desa</option>
                    @foreach ($list_desa as $desa)
                        <option value="{{ $desa->desa_id }}" <?php $cari_desa == $desa->desa_id && (print 'selected'); ?>>{{ $desa->nama }} </option>
                    @endforeach
                </select>
                <select class="form-control" id="tanggal" name="tanggal" style="display: inline-flex; width: auto;">
                    <option value="Terlama">Terbaru</option>
                    <option value="Terbaru">Terlama</option>
                </select>
                <div class="input-group input-group-sm" style="display: inline-flex; float: right; padding: 5px;">
                    <input class="form-control" style="height: auto;" type="text" name="cari" placeholder="Cari berita" value="{{ $cari }}" />
                    <button type="submit" class="btn btn-info btn-block" style="width: auto;">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
        @include('pages.berita.feeds')

    </div>
@endsection
