@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Data Umum</a></li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        {!! Form::model($data_umum, [
            'route' => ['data.data-umum.update', $data_umum->id],
            'method' => 'put',
            'id' => 'form-event',
            'class' => 'form-horizontal form-label-left',
        ]) !!}

        <input type="hidden" name="path" id="path" value="{{ $data_umum->path }}">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#wilayah" role="tab" aria-controls="wilayah" id="wilayahTab" data-toggle="tab">Info Wilayah</a></li>
                <li role="presentation"><a href="#peta" role="tab" aria-controls="peta" data-toggle="tab">Peta
                        Wilayah</a></li>
                <li role="presentation"><a href="#lokasi-kantor" role="tab" aria-controls="lokasi_kantor" data-toggle="tab">Lokasi Kantor</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="wilayah">
                    <div class="box-body">
                        @include('data.data_umum.form_edit')
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="peta">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="tampil-map" style="height:500px">
                                <div class="text-center" style="margin-top: 35vh">
                                    <h1>Memuat Peta</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                    <a id="reset">
                        <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i>&nbsp;
                            Reset Peta</button>
                    </a>
                </div>

                <div role="tabpanel" class="tab-pane" id="lokasi-kantor">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="tampil-lokasi-kantor" style="height:500px">
                                <div class="text-center" style="margin-top: 35vh">
                                    <h1>Memuat Lokasi Kantor</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="lat">Latitude</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-sm lat" name="lat" id="lat" value="{{ $data_umum->lat }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="lat">Longitude</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-sm lng" name="lng" id="lng" value="{{ $data_umum->lng }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                @include('partials.button_reset_submit')
            </div>
        </div>

        {!! Form::close() !!}
    </section>
@endsection

@include('partials.tinymce_min')
@include('partials.asset_select2')
@include('partials.asset_sweetalert')
@include('partials.asset_leaflet')

@push('scripts')
    <script>
        function validateForm() {
            var myForm = document.getElementById('form-event');
            // Check if the form is valid
            if (!myForm.checkValidity()) {
                // The form is not valid, show an alert or perform other actions
                document.getElementById('wilayahTab').click();
            }
        }

        $(function() {
            $('input[type=number]').attr('min', 0)
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                if (e.target.hash == '#peta') {
                    $.when(path_desa()).done(function(res_desa) {
                        if (res_desa) {
                            var marker_desa = new Array();
                            var marker;
                            res_desa.data.forEach(e => {
                                if (e.path != null) {
                                    marker = set_marker(e, 'Peta Wilayah Desa',
                                        'Wilayah Desa ' + e
                                        .nama, {
                                            'line': '#de2d26',
                                            'fill': '#fff'
                                        });
                                    marker_desa = marker_desa.concat(marker);
                                }
                            });
                            overlayLayers['Peta Wilayah Desa'] = wilayah_property(marker_desa,
                                false);
                            tampil_peta();
                        }
                    });
                }

                if (e.target.hash == '#lokasi-kantor') {
                    tampil_lokasi_kantor()
                }

            })
            // on page loaded
            updateValueLuasWilayah();
            $('#provinsi_id').select2({
                placeholder: "Pilih Provinsi",
                allowClear: true
            });
            $('#kabupaten_id').select2({
                placeholder: "Pilih Kabupaten",
                allowClear: true
            });
            $('#kecamatan_id').select2({
                placeholder: "Pilih Kecamatan",
                allowClear: true
            });
            $(".sumber_luas_wilayah").change(function() {
                updateValueLuasWilayah();
            });

            function path_desa() {
                return $.ajax({
                        type: "get",
                        url: "{{ route('data.data-desa.getdataajax') }}",
                        dataType: 'json',
                        success: function(response) {
                            return response
                        }
                    })
                    .fail(function() {
                        return false;
                    });
            }
        })

        function updateValueLuasWilayah() {
            var sumberLuasWilayah = $(".sumber_luas_wilayah").val();
            $.ajax({
                url: "data-umum/getdataajax",
                type: "get",
                success: function(response) {
                    if (sumberLuasWilayah == 1) {
                        $(".luas_wilayah").val(response.data.luas_wilayah);
                        $(".luas_wilayah").attr('readonly', false);
                    } else {
                        $(".luas_wilayah").val(response.data.luas_wilayah_dari_data_desa);
                        $(".luas_wilayah").attr('readonly', true);
                    }
                },
                error: function(xhr) {
                    console.log('terjadi kesalahan');
                }
            });
        }

        var overlayLayers = {};

        function tampil_peta() {
            var mapboxToken = document.querySelector('meta[name="mapbox-token"]').getAttribute('content');
            var mapboxDefault = document.querySelector('meta[name="mapbox-default"]').getAttribute('content');
            // Inisialisasi tampilan peta
            var posisi = [-1.0546279422758742, 116.71875000000001];
            var zoom = 13;
            var peta_wilayah = L.map('tampil-map', {
                center: posisi,
                zoom: zoom
            });

            var path_kec = new Array();
            if ($('#path').val() != '') {
                path_kec = JSON.parse($('#path').val());
                showPolygon(path_kec, peta_wilayah)
            }
            // Geolocation IP Route/GPS
            geoLocation(peta_wilayah);

            var baseLayers = getBaseLayers(peta_wilayah, mapboxToken, mapboxDefault);
            L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
            }).addTo(peta_wilayah);
            // add toolbar
            peta_wilayah.pm.addControls(editToolbarPoly());
            addpoly(peta_wilayah);
            // Menghapus Peta wilayah
            hapuslayer(peta_wilayah);
            // Export/Import Peta dari file GPX
            eximGpxRegion(peta_wilayah);
            // Import Peta dari file SHP
            eximShp(peta_wilayah);

            peta_wilayah.on('pm:update', function(e) {
                setPupup(e.layer);
            });

            function makePopupContent(feature) {
                return
                feature.geometry;
            }
            $('.leaflet-bar-part.leaflet-bar-part-single .fa-map-marker').css('font-size', '24px')
        };

        function tampil_lokasi_kantor() {
            // Inisialisasi tampilan peta
            var posisi = [{{ $data_umum->lat ?? -1.0546279422758742 }}, {{ $data_umum->lng ?? 116.71875000000001 }}];
            const zoom = 13;
            var lokasi_kantor = L.map('tampil-lokasi-kantor', {
                center: posisi,
                zoom: zoom
            });

            var baseLayers = getBaseLayers(lokasi_kantor, '');
            L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
            }).addTo(lokasi_kantor);

            // Menampilkan dan Menambahkan Peta wilayah + Geolocation GPS
            showCurrentPoint(posisi, lokasi_kantor);
            $('.leaflet-bar-part.leaflet-bar-part-single .fa-map-marker').css('font-size', '24px')
        };

        $('#reset').on('click', function() {
            Swal.fire({
                title: 'Apakah anda yakin ingin mereset peta?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Tidak!',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`{{ route('data.data-umum.resetpeta', $data_umum->id) }}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            )
                        })
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Sukses!',
                        'Peta berhasil direset.',
                        'success'
                    )
                    return window.location.replace(`{{ route('data.data-umum.index') }}`);
                } else {
                    Swal.fire(
                        'Gagal!',
                        'Peta gagal direset.',
                        'error'
                    )
                }
            })
        });
    </script>
@endpush
