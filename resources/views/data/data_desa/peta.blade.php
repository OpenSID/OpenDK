@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('data.data-desa.index') }}">Data Desa</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content container-fluid">
  <div class="box box-primary">
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
            <div id="tampil-map" style="height: calc(100VH - 110px);">
              <div class="text-center" style="margin-top: 35vh"><h1>Memuat Peta</h1></div>
            </div>
            <input type="hidden" id="path">
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@include(('partials.asset_leaflet'))
@push('scripts')
<script>
    var overlayLayers = {};
    function tampil_peta () { 
      // Inisialisasi tampilan peta
      var posisi = [-1.0546279422758742, 116.71875000000001];
      var zoom = 10;
      var peta_wilayah = L.map('tampil-map', {
        center: posisi,
        zoom: 13
      });

      var path_desa = {{ empty($desa->path)? 'new Array()': $desa->path }};
      if (path_desa.length == 0) {
        $('#tampil-map div.text-center').html(`<h1>Peta Tidak Tersedia</h1>`)
      }
      // Geolocation IP Route/GPS
      geoLocation(peta_wilayah);
      showPolygon(path_desa, peta_wilayah)

      var baseLayers = getBaseLayers(peta_wilayah, '');
      L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
      }).addTo(peta_wilayah);
    };

    $(function () {
      $('#list_desa').change(function () {
          $("#desa_id").val($('#list_desa').val());
          $("#nama").val($('#list_desa option:selected').text());
      });

      //call ajax peta kecamatan;
     function path_kec () {
        return $.ajax({
          type: "get",
          url: "{{ route('data.data-umum.getdataajax') }}",
          dataType: 'json',
          success: function (response) {
            return response
          }
        })
        .fail(function() {
          return false;
        });
      }
      
      $.when(path_kec()).done(function(res_kec){
        if (res_kec && res_kec.data != null) {
          var mark_kec = set_marker(res_kec.data, 'Peta Wilayah Kecamatan', 'Wilayah Kecamatan ' + res_kec.data.profil.nama_kecamatan, {'line' : '#de2d26', 'fill' : '#fff'});
          overlayLayers['Peta Wilayah Kecamatan'] =  wilayah_property(mark_kec, false);
        }
        tampil_peta();
      });
    });
</script>
@endpush