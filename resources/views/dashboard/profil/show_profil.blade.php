@extends('layouts.dashboard_template')

@section('title') Profil @endsection

@section('content')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title or "Page Title" }}
        <small>{{ $page_description or null }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    @if(!empty($profil))
    <div class="box box-primary hidden">
        <div class="box-header with-border">
            <div class="col-sm-12">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="kecamatan" class="col-sm-2 control-label">Kecamatan</label>

                        <div class="col-sm-4">
                            <input type="hidden" id="defaultProfil" value="{{ $defaultProfil }}">
                            <select class="form-control" id="kecamatan" name="kecamatan" onchange=""></select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <div id="profil-kecamatan">
        <div class="row">
            <div class="col-md-4">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-blue" style="padding:5px;">
                    @if(! $profil->file_logo == '')
                      <div class="widget-user-image">
                        <img class="img-rounded" src="{{ asset($profil->file_logo) }}" alt="Logo Kecamatan">
                      </div>
                    @else
                        <div class="widget-user-image">
                            <img class="img-rounded" src="{{ asset('/img/no-image.png') }}" alt="Logo Kecamatan">
                        </div>
                    @endif
                  <!-- /.widget-user-image -->
                  <h3 style="padding-top:15px; padding-left:0px; padding-bottom:15px;" class="widget-user-username">{{ ucwords(strtolower($profil->kecamatan->nama)) }}</h3>
                </div>
              
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                    <li><a href="#"><strong>Luas Wilayah </strong><span class="pull-right badge bg-aqua" id="luaswilayah">{{ number_format($profil->dataumum->luas_wilayah) }} km</span></a></li>
                    <li><a href="#"><strong>Jumlah Penduduk </strong><span class="pull-right badge bg-aqua" id="jumlahpenduduk">{{ number_format($profil->dataumum->jumlah_penduduk) }} orang</span></a></li>
                    <li><a href="#"><strong>Kepadatan Penduduk </strong><span class="pull-right badge bg-aqua" id="kepadatanpenduduk">{{ number_format($profil->dataumum->kepadatan_penduduk) }} orang/km</span></a></li>
                    <li><a href="#"><strong>Kelurahan/Desa </strong><span class="pull-right badge bg-aqua" id="kelurahandesa">{{ number_format(count($profil->dataDesa)) }}</span></a></li>
                  </ul>
                </div>
              </div>
              <!-- /.widget-user -->

                <!-- /.box -->

                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informasi</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <strong><i class="fa fa-file-text-o margin-r-5"></i> Form-Form Pelayanan</strong>

                        <div id="InfoPelayanan" style="margin:0px 0px 0px 0px;">
                            <ul class="nav nav-stacked">
                                @foreach($dokumen as $item)
                                <li><a href="{{ asset($item->file_dokumen) }}"><i class="fa fa-circle"></i> {{ $item->nama_dokumen }}</a></li>
                                @endforeach
                                <li class="footer"><center><a href="{{ route('informasi.form-dokumen.index') }}">Lihat Semua</a></center></li>
                            </ul>
                        </div>
                        <hr>

                        <strong><i class="fa fa-book margin-r-5"></i> Kontak</strong>
                        <br><br>

                        <p id="kontakdetail" style="font-size: 8pt;">

                        <p style="text-align: center;"><strong>Kantor Camat {{ ucwords(strtolower($profil->kecamatan->nama)) }}<br></strong> {!! $profil->alamat !!}, {!! $profil->kode_pos !!}<br>Telp: {!! $profil->telepon !!}/e-mail: {!! $profil->email !!}</p></p><p>
                        </p></div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-8">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#peta" data-toggle="tab">Peta</a></li>
                        <li><a href="#profil" data-toggle="tab">Profil</a></li>
                        <li><a href="#data-umum" data-toggle="tab">Data Umum</a></li>
                        <li><a href="#organisasi" data-toggle="tab">Struktur Organisasi</a></li>
                        <li><a href="#desa" data-toggle="tab">Desa</a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="profil">
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <th class="col-md-4">Nama Kecamatan</th>
                                    <td class="col-md-8">: {{ ucwords(strtolower($profil->kecamatan->nama)) }}</td>
                                </tr>
                                <tr>
                                    <th class="col-md-4">Kode Kecamatan</th>
                                    <td class="col-md-8">: {{ ucwords(strtolower($profil->kecamatan->kode)) }}</td>
                                </tr>

                                <tr>
                                    <th class="col-md-4">Tahun Pembentukan</th>
                                    <td class="col-md-8">: {{ $profil->tahun_pembentukan }}</td>
                                </tr>

                                <tr>
                                    <th class="col-md-4">Dasar Hukum Pembentukan</th>
                                    <td class="col-md-8">: {{ $profil->dasar_pembentukan }}</td>
                                </tr>

                                <tr>
                                    <th class="col-md-4">Provinsi</th>
                                    <td class="col-md-8">: {!! $profil->provinsi->id !!} - {!! ucwords(strtolower($profil->provinsi->nama)) !!}</td>
                                </tr>

                                <tr>
                                    <th class="col-md-4">Kabupaten/Kota</th>
                                    <td class="col-md-8">: {!! ucwords($profil->kabupaten->id) !!} - {!! ucwords(strtolower($profil->kabupaten->nama)) !!}</td>
                                </tr>

                                <tr>
                                    <th class="col-md-4">Kode Pos</th>
                                    <td class="col-md-8">: {!! ucwords($profil->kode_pos) !!}</td>
                                </tr>

                                <tr>
                                    <th class="col-md-4">Kabupaten/Kota</th>
                                    <td class="col-md-8">: {!! ucwords(strtolower($profil->kabupaten->nama)) !!}</td>
                                </tr>

                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-6">
                                    <legend>Visi</legend>
                                    {!! $profil->visi !!}
                                </div>
                                <div class="col-md-6">
                                    <legend>Misi</legend>
                                    {!! $profil->misi !!}
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="data-umum">
                            <!-- The timeline -->
                            <div class="row">
                                <div class="col-md-6">
                                    <legend>Info Wilayah</legend>
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th class="col-md-4">Tipologi</th>
                                            <td class="col-md-8">: {!! ucwords($profil->dataumum->tipologi ) !!}</td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-4">Ketinggian</th>
                                            <td class="col-md-8">: {!! number_format($profil->dataumum->ketinggian) !!} (MDPL)</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-2">Luas Wilayah</th>
                                            <td class="col-md-9">: {{ number_format($profil->dataumum->luas_wilayah) }}
                                                <span>km<sup>2</sup></span></td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-2">Dasar Hukum Pembentukan</th>
                                            <td class="col-md-9">:  {!! $profil->dasar_pembentukan !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-2">Kode Pos</th>
                                            <td class="col-md-9">: {!! ucwords($profil->kode_pos) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-2">Kabupaten/Kota</th>
                                            <td class="col-md-9">: {!! ucwords(strtolower($profil->kabupaten->nama)) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-2">Jumlah Penduduk</th>
                                            <td class="col-md-9">: {!! number_format($profil->dataumum->jumlah_penduduk) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-2">Laki Laki</th>
                                            <td class="col-md-9">: {!! number_format($profil->dataumum->jml_laki_laki) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-2">Perempuan</th>
                                            <td class="col-md-9">: {!! number_format( $profil->dataumum->jml_perempuan) !!}</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <br>
                                    <legend>Batas Wilayah</legend>
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th class="col-md-2">Utara</th>
                                            <td class="col-md-9">
                                                : {!! ucwords($profil->dataumum->bts_wil_utara) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-2">Timur</th>
                                            <td class="col-md-9">: {!! $profil->dataumum->bts_wil_timur !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-2">Selatan</th>
                                            <td class="col-md-9">: {!! $profil->dataumum->bts_wil_selatan!!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-2">Barat</th>
                                            <td class="col-md-9">: {!! $profil->dataumum->bts_wil_barat !!}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <legend>Jumlah Sarana & Prasarana</legend>

                                    <h4>A. Sarana Kesehatan</h4>
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th class="col-md-6">1. Puskesmas</th>
                                            <td class="col-md-6">
                                                : {!! number_format($profil->dataumum->jml_puskesmas) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-6">2. Puskesmas Pembantu</th>
                                            <td class="col-md-6">
                                                : {!! number_format($profil->dataumum->jml_puskesmas_pembantu) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-6">3. Posyandu</th>
                                            <td class="col-md-6">: {!! number_format($profil->dataumum->jml_posyandu) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-6">4. Pondok Bersalin</th>
                                            <td class="col-md-6">: {!! number_format($profil->dataumum->jml_pondok_bersalin) !!}</td>
                                        </tr>

                                        </tbody>
                                    </table>

                                    <h4>B. Sarana Pendidikan</h4>
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th class="col-md-6">1. PAUD/Sederajat</th>
                                            <td class="col-md-6">: {!! number_format($profil->dataumum->jml_paud) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-6">2. SD/Sederajat</th>
                                            <td class="col-md-6">: {!! number_format($profil->dataumum->jml_sd) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-6">3. SMP/Sederajat</th>
                                            <td class="col-md-6">: {!! number_format($profil->dataumum->jml_smp) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-6">4. SMA/Sederajat</th>
                                            <td class="col-md-6">: {!! number_format($profil->dataumum->jml_sma) !!}</td>
                                        </tr>

                                        </tbody>
                                    </table>

                                    <h4>C. Sarana Umum</h4>
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th class="col-md-6">1. Masjid Besar</th>
                                            <td class="col-md-6">: {!! number_format($profil->dataumum->jml_masjid_besar) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-6">2. Gereja</th>
                                            <td class="col-md-6">: {!! number_format($profil->dataumum->jml_gereja) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-6">3. Pasar</th>
                                            <td class="col-md-6">: {!! number_format($profil->dataumum->jml_pasar) !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-6">4. Balai Pertemuan</th>
                                            <td class="col-md-6">: {!! number_format($profil->dataumum->jml_balai_pertemuan) !!}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="organisasi">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th class="col-md-3">Camat</th>
                                            <td class="col-md-8">: {!! $profil->nama_camat !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-3">Sekretaris Camat</th>
                                            <td class="col-md-8">: {!! $profil->sekretaris_camat !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-3">Kepala Seksi Pemerintahan Umum</th>
                                            <td class="col-md-8">: {!! $profil->kepsek_pemerintahan_umum !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-3">Kepala Seksi Kesejahteraan Masyarakat</th>
                                            <td class="col-md-8">: {!! $profil->kepsek_kesejahteraan_masyarakat !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-3">Kepala Seksi Pemberdayaan Masyarakat</th>
                                            <td class="col-md-8">: {!! $profil->kepsek_pemberdayaan_masyarakat !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-3">Kepala Seksi Pelayanan Umum</th>
                                            <td class="col-md-8">: {!! $profil->kepsek_pelayanan_umum !!}</td>
                                        </tr>

                                        <tr>
                                            <th class="col-md-3">Kepala Seksi Ketentraman dan Ketertiban</th>
                                            <td class="col-md-8">: {!! $profil->kepsek_trantib !!}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                <legend>Struktur Organisasi</legend>
                                <div class="col-md-12"><img class="col-md-12" id="strukturpic" src="@if(! $profil->file_struktur_organisasi =='') {{ asset($profil->file_struktur_organisasi) }} @else {{ 'http://placehold.it/700x400' }} @endif"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="desa">
                            <div class="row">
                              <div class="col-md-5 col-sm-6 col-xs-12">
                                <ul class="nav nav-stacked">
                                  @foreach($profil->datadesa as $desa)
                                  <li><a class="nav-item" target="_parent" @if($desa->website != '') href="{{ $desa->website}}"  title="Masuk Ke Website Desa" @else href="#" title="Website Desa Tidak Tersedia!" @endif>{{ $desa->nama }}<span class="pull-right" >@if($desa->website != '') <i class="fa fa-globe"></i> @endif</span></a></li>
                                  @endforeach
                                </ul>                                
                              </div>  
                            </div>
                        </div>

                        <div class="active tab-pane" id="peta">
                            <!-- /.box-header -->
                            <div class="box-body no-padding">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div  id="canvas_peta">
                                        <iframe src="{!! $profil->dataumum->embed_peta !!}" frameborder="0" style="border:0; width:100%; height:600px; margin: 0px!;" allowfullscreen></iframe>
                                        </div>
                                      </iframe>
                                                <!-- /.col -->
                                    </div>
                                    <!-- /.row -->
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        Data profil tidak ditemukan!
                    </div>
                    <div class="box-body">
                        Silahkan hubungi Administrator untuk menambahkan data profil.
                    </div>
                </div>
            </div>
        </div>

    @endif
</section>
<!-- /.content -->
@endsection

@push('scripts')
<script type="application/javascript" src="{{ asset('js/html2canvas.min.js') }}"></script>
<script type="application/javascript">
    document.querySelector("#btn_peta").addEventListener("click", function() {

        html2canvas(document.iframe).then(function(canvas) {
            document.body.appendChild(canvas);
        });

    }, false);

</script>
@endpush
