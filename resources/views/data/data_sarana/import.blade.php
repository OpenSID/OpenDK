@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>{{ $page_title }}</h1>
    <small>{{ $page_description }}</small>
</section>

<section class="content container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Import Data Sarana</h3>
        </div>
        <div class="panel-body">
            <form action="{{ route('data.data-sarana.import-excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file">Pilih File Excel</label>
                    <div class="input-group">
                        <label class="input-group-btn">
                            <span class="btn btn-primary">
                                Browse&hellip; <input type="file" name="file" style="display: none;" required>
                            </span>
                        </label>
                        <input type="text" class="form-control" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Import</button>
            </form>

            <script>
            document.querySelector('input[type=file]').addEventListener('change', function(e){
                var fileName = e.target.files[0].name;
                this.closest('.input-group').querySelector('input.form-control').value = fileName;
            });
            </script>

            <h4>Contoh Format Import Excel</h4>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>desa_id</th>
                        <th>nama</th>
                        <th>jumlah</th>
                        <th>kategori</th>
                        <th>keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Posyandu Melati</td>
                        <td>3</td>
                        <td>puskesmas</td>
                        <td>Bangunan permanen</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>PAUD Tunas Bangsa</td>
                        <td>2</td>
                        <td>paud</td>
                        <td>Kondisi baik</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Pasar</td>
                        <td>1</td>
                        <td>pasar</td>
                        <td>Perlu perbaikan pagar</td>
                    </tr>
                </tbody>
            </table>
            <h4>List Kategori Data Sarana</h4>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Sub Kategori</th>
                        <th>Value (Untuk kategori import)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sarana Kesehatan -->
                    <tr>
                        <td rowspan="4">Sarana Kesehatan</td>
                        <td>Puskesmas</td>
                        <td>puskesmas</td>
                    </tr>
                    <tr>
                        <td>Puskesmas Pembantu</td>
                        <td>puskesmas_pembantu</td>
                    </tr>
                    <tr>
                        <td>Posyandu</td>
                        <td>posyandu</td>
                    </tr>
                    <tr>
                        <td>Pondok Bersalin</td>
                        <td>pondok_bersalin</td>
                    </tr>

                    <!-- Sarana Pendidikan -->
                    <tr>
                        <td rowspan="4">Sarana Pendidikan</td>
                        <td>PAUD/Sederajat</td>
                        <td>paud</td>
                    </tr>
                    <tr>
                        <td>SD/Sederajat</td>
                        <td>sd</td>
                    </tr>
                    <tr>
                        <td>SMP/Sederajat</td>
                        <td>smp</td>
                    </tr>
                    <tr>
                        <td>SMA/Sederajat</td>
                        <td>sma</td>
                    </tr>

                    <!-- Sarana Umum -->
                    <tr>
                        <td rowspan="5">Sarana Umum</td>
                        <td>Masjid Besar</td>
                        <td>masjid_besar</td>
                    </tr>
                    <tr>
                        <td>Mushola</td>
                        <td>mushola</td>
                    </tr>
                    <tr>
                        <td>Gereja</td>
                        <td>gereja</td>
                    </tr>
                    <tr>
                        <td>Pasar</td>
                        <td>pasar</td>
                    </tr>
                    <tr>
                        <td>Balai Pertemuan</td>
                        <td>balai_pertemuan</td>
                    </tr>
                </tbody>
            </table>

            <h4>List Id Desa</h4>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Desa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($desas as $desa)
                        <tr>
                            <td>{{ $desa->id }}</td>
                            <td>{{ $desa->nama }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">Belum ada data desa</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
