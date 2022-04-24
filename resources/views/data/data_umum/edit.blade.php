@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Data Umum</a></li>
    </ol>
</section>

<section class="content container-fluid">

    @include( 'partials.flash_message' )

    <div class="box box-primary">

        @if(count($errors) > 0)

            <div class="alert alert-danger">
                <strong>Ups!</strong> Ada beberapa masalah dengan masukan Anda.<br><br>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif

        <!-- form start -->
        {!! Form::model($data_umum, [ 'route' => ['data.data-umum.update', $data_umum->id], 'method' => 'put','id' =>
        'form-event', 'class' => 'form-horizontal form-label-left' ] ) !!}
        <input type="hidden" name="path" id="path" value="{{ $data_umum->path }}">

        <div class="box-body">
            @include('data.data_umum.form_edit')
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="pull-right">
                <div class="control-group">
                    <a href="{{ route('data.data-umum.index') }}">
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i>&nbsp;
                            Batal</button>
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i>&nbsp;
                        Simpan</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</section>
@endsection

@include('partials.asset_wysihtml5')
@include(('partials.asset_select2'))
@include(('partials.asset_leaflet'))
@push('scripts')
    <script>
        $(function () {
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
            $(".sumber_luas_wilayah").change(function(){
                updateValueLuasWilayah();
            }); 
        })

        function updateValueLuasWilayah(){
            var sumberLuasWilayah = $(".sumber_luas_wilayah").val();
            $.ajax({
                url: "data-umum/getdataajax",
                type: "get",
                success: function(response) {
                    if(sumberLuasWilayah == 1) {
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
        $('.textarea').wysihtml5();
         
        $(function () {
            function path_desa () {
                return $.ajax({
                type: "get",
                url: "{{ route('data.data-desa.getdataajax') }}",
                dataType: 'json',
                success: function (response) {
                    return response
                }
                })
                .fail(function() {
                    return false;
                });
            }
            
            $.when(path_desa()).done(function(res_desa){
        
                if (res_desa) {
                    var marker_desa = new Array();
                    var marker;
                    res_desa.data.forEach(e => {
                        if (e.path != null) {
                            marker = set_marker(e, 'Peta Wilayah Desa', 'Wilayah Desa ' + e.nama, {'line' : '#de2d26', 'fill' : '#fff'});
                            marker_desa =  marker_desa.concat(marker);
                        }
                    });
                    overlayLayers['Peta Wilayah Desa'] =  wilayah_property(marker_desa, false);
                    tampil_peta();
                }
            });
        });
        var overlayLayers = {};
        function tampil_peta () { 
            // Inisialisasi tampilan peta
            var posisi = [-1.0546279422758742, 116.71875000000001];
            var zoom = 10;
            var peta_wilayah = L.map('tampil-map', {
                center: posisi,
                zoom: 13
            });
            
            var path_kec = new Array();
            if ($('#path').val() != '') {
                path_kec = JSON.parse($('#path').val());
                showPolygon(path_kec, peta_wilayah)
            }
            // Geolocation IP Route/GPS
            geoLocation(peta_wilayah);
        
            var baseLayers = getBaseLayers(peta_wilayah, '');
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
            peta_wilayah.on('pm:update', function (e) {
                setPupup(e.layer);
            });
            function makePopupContent(feature) {
                return
                feature.geometry;
            }
        };
    </script>
 
@endpush