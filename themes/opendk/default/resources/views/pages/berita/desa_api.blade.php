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
        <div class="page-header" style="margin:0px 0px;">
            <strong>Berita {{ config('setting.sebutan_desa') }}</strong>
        </div>
        <div class="page-header" style="margin:0px 0px; padding: 0px;">
            <div class="row page-header-row">
                <div class="col-md-8 page-header-left">
                    @include('layouts.fragments.select-desa')
                    <select class="form-control select2" id="tanggal">
                        <option value="Terbaru">Terbaru</option>
                        <option value="Terlama">Terlama</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm" style="display: inline-flex; float: right; padding: 5px;">
                        <input class="form-control" type="text" id="cari" placeholder="Cari berita" style="height: auto;" />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-info" id="btn-cari"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="placeholder-loading" style="display:none">@include('components.placeholder')</div>
        <div id="desa">
            <div class="post clearfix"></div>
        </div>
        @include('components.pagination')
        @include('partials.asset_select2')

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            let apiBase = "{!! $apiBase !!}";
            let currentApiBase = apiBase;

            function renderArtikel(items) {
                if (!items || items.length === 0) {
                    return '<div class="callout callout-info"><p class="text-bold">Tidak ada berita desa yang ditampilkan!</p></div>';
                }

                return items.map(function(single) {
                    const item = single.attributes;
                    var isi = (item.isi || '').replace(/(<([^>]+)>)/gi, "");
                    var excerpt = isi.length > 250 ? isi.substr(0, 250) + '...' : isi;
                    var tanggal = item.tanggal_terbit ? new Date(item.tanggal_terbit).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    }) : '';

                    return `<div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">
                <div class="row">
                    <div class="col-sm-4">
                        <img class="img-responsive" src="${item.gambar_src}" alt="${item.judul || ''}">
                    </div>
                    <div class="col-sm-8">
                        <h5 style="margin-top: 5px; text-align: justify;"><b><a href="${item.link || '#'}">${item.judul || ''}</a></b></h5>
                        <p style="font-size:11px;">
                            <i class="fa fa-calendar"></i>&ensp;${tanggal}&ensp;|&ensp;
                            <i class="fa fa-user"></i>&ensp;Administrator
                        </p>
                        <p style="text-align: justify;">${excerpt}</p>
                        <a href="${item.link || '#'}" class="btn btn-sm btn-primary" target="_blank">Selengkapnya</a>
                    </div>
                </div>
            </div>`;
                }).join('');
            }

            function buildUrl(pageNumber) {
                var url = currentApiBase;
                if (pageNumber) url += '&page[number]=' + pageNumber;
                return url;
            }

            function loadArtikel(pageNumber) {
                var $container = $('#desa .post.clearfix');
                $container.html($('#placeholder-loading').html());

                $.getJSON(buildUrl(pageNumber))
                    .done(function(res) {
                        var items = res.data || res;
                        var html = renderArtikel(items);
                        $container.html(html);

                        if (res.meta && res.meta.pagination) {
                            initPagination(res, function() {
                                $('.pagination').on('click', '.btn-page', function() {
                                    var page = $(this).data('page');
                                    loadArtikel(page);
                                });
                                $('.pagination').find('.btn-page').attr('href', '#desa');
                            });
                        } else {
                            $('.pagination').empty();
                        }
                    })
                    .fail(function() {
                        $container.html('<div class="callout callout-danger"><p class="text-bold">Gagal mengambil data berita desa.</p></div>');
                    });
            }

            function applyFilter() {
                var base = apiBase;
                var desa = $('#list_desa').val();
                var tanggal = $('#tanggal').val();
                var cari = $('#cari').val().trim();

                if (desa && desa !== 'Semua') base += '&filter[kode_desa]=' + encodeURIComponent(desa);
                if (cari) base += '&filter[search]=' + encodeURIComponent(cari);
                if (tanggal === 'Terlama') base += '&sort=tgl_upload';

                currentApiBase = base;
                loadArtikel(1);
            }

            $('#list_desa').change(function() {
                applyFilter();
            });
            $('#tanggal').change(function() {
                applyFilter();
            });
            $('#btn-cari').click(function() {
                applyFilter();
            });
            $('#cari').on('keypress', function(e) {
                if (e.which === 13) applyFilter();
            });

            loadArtikel(1);
        });
    </script>
@endpush
