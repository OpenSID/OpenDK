@extends('layouts.app')

@section('title', 'Letak Geografis')

@section('content')
{{-- <section class="content"> --}}

    <!-- /.row -->
    <!-- /.box-header -->
    {{-- <div class="row"> --}}
    <div class="col-md-8 col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border text-bold">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> LETAK
                    GEOGRAFIS <span id="nama-kecamatan"></span></h3>
            </div>
            <div class="box-body">
                <div id="canvas_peta" style="border:0; width:100%; height:600px; margin: 0px!;"></div>
                <input type="hidden" name="path" id="path">
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="box-footer">
            <p style="text-align: justify">
                <span id="nama-wilayah-lengkap"></span>
                mempunyai <b> Luas <span id="luas-wilayah"></span> km<sup>2</sup></b>
                yang mencakup<b> <span id="jumlah-desa"></span> Desa/Kelurahan</b>. Adapun <b>
                    <span id="jumlah-desa-terbilang"></span> Desa/Kelurahan </b> tersebut yaitu :
            <ul id="list-desa">
            </ul>
            </p>

            <br />

            <h4 class="text-primary">Batas wilayah <span id="sebutan-wilayah"></span>
                <span id="nama-kecamatan-batas"></span> meliputi :
            </h4>
            <table>
                <tbody>
                    <tr>
                        <th class="col-md-2">Utara</th>
                        <td class="col-md-9">
                            : <span id="bts-wil-utara"></span></td>
                    </tr>

                    <tr>
                        <th class="col-md-2">Timur</th>
                        <td class="col-md-9">: <span id="bts-wil-timur"></span></td>
                    </tr>

                    <tr>
                        <th class="col-md-2">Selatan</th>
                        <td class="col-md-9">: <span id="bts-wil-selatan"></span></td>
                    </tr>

                    <tr>
                        <th class="col-md-2">Barat</th>
                        <td class="col-md-9">: <span id="bts-wil-barat"></span></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

{{-- </div> --}}
{{-- </section> --}}
@endsection
@include('partials.asset_leaflet')
@push('scripts')
<script type="application/javascript">
    var overlayLayers = {};

    function tampil_peta(path) {
        // Inisialisasi tampilan peta
        var posisi = [-1.0546279422758742, 116.718750000001];
        var zoom = 10;
        var peta_wilayah = L.map('canvas_peta', {
            center: posisi,
            zoom: 13
        });

        var path_kec = path;
        // Geolocation IP Route/GPS
        geoLocation(peta_wilayah);
        showPolygon(path_kec, peta_wilayah)


        var baseLayers = getBaseLayers(peta_wilayah, '');
        L.control.layers(baseLayers, overlayLayers, {
            position: 'topleft',
            collapsed: true
        }).addTo(peta_wilayah);
    };

    $(function() {
        // Update content with websiteData.profile when it's available
        $(document).on('websiteDataLoaded', function(event, websiteData) {
            var marker_desa = new Array();
            var marker;
            var profile = null,
                desa = [];
            if (websiteData.profile) {
                profile = websiteData.profile;
                desa = websiteData.desa
            }

            desa.forEach(e => {
                if (e.path != null) {
                    marker = set_marker(e, 'Peta Wilayah Desa', 'Wilayah Desa ' + e.nama, {
                        'line': '#de2d26',
                        'fill': '#fff'
                    });
                    marker_desa = marker_desa.concat(marker);
                }
            });

            overlayLayers['Peta Wilayah Desa'] = wilayah_property(marker_desa, false);

            if (profile) {
                // Update header
                $('#nama-kecamatan').text(profile.nama_kecamatan.toUpperCase());
                $('#nama-kecamatan-batas').text(profile.nama_kecamatan.toLowerCase());
                $('#sebutan-wilayah').text('{{ config('profil.sebutan_wilayah') }}');
                $('#nama-wilayah-lengkap').text([
                    '{{ config('profil.sebutan_wilayah') }}',
                    profile.nama_kecamatan,
                    'Kabupaten',
                    profile.nama_kabupaten,
                    'Provinsi',
                    profile.nama_provinsi
                ].join(' '));

                // Update geographical data
                $('#luas-wilayah').text(profile.data_umum ? parseFloat(profile.data_umum.luas_wilayah_value).toLocaleString() : 'N/A');
                $('#jumlah-desa').text(desa.length);
                $('#jumlah-desa-terbilang').text(terbilang(desa.length));

                // Update borders
                $('#bts-wil-utara').text(profile.data_umum ? profile.data_umum.bts_wil_utara : '');
                $('#bts-wil-timur').text(profile.data_umum ? profile.data_umum.bts_wil_timur : '');
                $('#bts-wil-selatan').text(profile.data_umum ? profile.data_umum.bts_wil_selatan : '');
                $('#bts-wil-barat').text(profile.data_umum ? profile.data_umum.bts_wil_barat : '');

                // Update list of villages
                var desaListHtml = '';
                if (desa.length > 0) {
                    desa.forEach(function(item) {
                        desaListHtml += `<li>${item.sebutan_desa}  ${item.nama} </li>`;
                    });
                }
                $('#list-desa').html(desaListHtml);
                tampil_peta(profile.data_umum.path);
            }
        });
    });
</script>
@endpush