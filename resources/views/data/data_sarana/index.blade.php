@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Data Sarana</li>
    </ol>
</section>

<section class="content container-fluid">
    <div class="box box-primary">
        <div class="box-header with-border">
            <form method="GET" action="{{ route('data.data-sarana.index') }}" class="form-inline">
                <div class="form-group">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari sarana...">
                </div>

                <div class="form-group">
                    <select name="kategori" class="form-control">
                        <optgroup label="Sarana Kesehatan">
                            <option value="puskesmas" {{ request('kategori') == 'puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                            <option value="puskesmas_pembantu" {{ request('kategori') == 'puskesmas_pembantu' ? 'selected' : '' }}>Puskesmas Pembantu</option>
                            <option value="posyandu" {{ request('kategori') == 'posyandu' ? 'selected' : '' }}>Posyandu</option>
                            <option value="pondok_bersalin" {{ request('kategori') == 'pondok_bersalin' ? 'selected' : '' }}>Pondok Bersalin</option>
                        </optgroup>
                        <optgroup label="Sarana Pendidikan">
                            <option value="paud" {{ request('kategori') == 'paud' ? 'selected' : '' }}>PAUD/Sederajat</option>
                            <option value="sd" {{ request('kategori') == 'sd' ? 'selected' : '' }}>SD/Sederajat</option>
                            <option value="smp" {{ request('kategori') == 'smp' ? 'selected' : '' }}>SMP/Sederajat</option>
                            <option value="sma" {{ request('kategori') == 'sma' ? 'selected' : '' }}>SMA/Sederajat</option>
                        </optgroup>
                        <optgroup label="Sarana Umum">
                            <option value="masjid_besar" {{ request('kategori') == 'masjid_besar' ? 'selected' : '' }}>Masjid Besar</option>
                            <option value="mushola" {{ request('kategori') == 'mushola' ? 'selected' : '' }}>Mushola</option>
                            <option value="gereja" {{ request('kategori') == 'gereja' ? 'selected' : '' }}>Gereja</option>
                            <option value="pasar" {{ request('kategori') == 'pasar' ? 'selected' : '' }}>Pasar</option>
                            <option value="balai_pertemuan" {{ request('kategori') == 'balai_pertemuan' ? 'selected' : '' }}>Balai Pertemuan</option>
                        </optgroup>
                    </select>
                </div>

                <div class="form-group">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>
                <div class="form-group">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter"></i> Filter
                </button>
                <a href="{{ route('data.data-sarana.index') }}" class="btn btn-default">
                    <i class="fa fa-refresh"></i> Reset
                </a>
                <a href="{{ route('data.data-sarana.export', [
                        'search' => request('search'),
                        'kategori' => request('kategori'),
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date'),
                    ]) }}" class="btn btn-success">
                    <i class="glyphicon glyphicon-download-alt"></i> Export
                </a>
                <a href="{{ route('data.data-sarana.create') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            </form>
        </div>

        <div class="box-body">
            @foreach($desas as $desa)
                <h3 class="text-primary" style="margin-top:30px;">
                    {{ $desa->nama }}
                </h3>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="bg-info">
                            <th style="width:25%">Nama Sarana</th>
                            <th style="width:25%">Jumlah</th>
                            <th style="width:25%">Kategori</th>
                            <th style="width:25%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($desa->saranas as $sarana)
                            <tr>
                                <td>{{ $sarana->nama }}</td>
                                <td>{{ $sarana->jumlah }}</td>
                                <td><span class="label label-primary">{{ $sarana->kategori ?? '-' }}</span></td>
                                <td>
                                    {{-- <a href="{{ route('data.data-sarana.show', $sarana->id) }}" class="btn btn-xs btn-info">
                                        <i class="fa fa-eye"></i> Lihat
                                    </a> --}}
                                    <a href="{{ route('data.data-sarana.edit', $sarana->id) }}" class="btn btn-xs btn-warning">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('data.data-sarana.destroy', ['id' => $sarana->id]) }}" 
                                        method="POST" 
                                        style="display:inline-block;" 
                                        onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada sarana</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endforeach
        </div>

        <div class="box-footer">
            {{ $desas->appends(request()->query())->links() }}
        </div>

        {{-- <div class="box-body">
            <h4>Rekap Total Sarana</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Puskesmas</td><td>{{ $rekapKategori['puskesmas'] ?? 0 }}</td></tr>
                    <tr><td>Puskesmas Pembantu</td><td>{{ $rekapKategori['puskesmas_pembantu'] ?? 0 }}</td></tr>
                    <tr><td>Posyandu</td><td>{{ $rekapKategori['posyandu'] ?? 0 }}</td></tr>
                    <tr><td>Pondok Bersalin</td><td>{{ $rekapKategori['pondok_bersalin'] ?? 0 }}</td></tr>
                    
                    <tr><td>PAUD/Sederajat</td><td>{{ $rekapKategori['paud'] ?? 0 }}</td></tr>
                    <tr><td>SD/Sederajat</td><td>{{ $rekapKategori['sd'] ?? 0 }}</td></tr>
                    <tr><td>SMP/Sederajat</td><td>{{ $rekapKategori['smp'] ?? 0 }}</td></tr>
                    <tr><td>SMA/Sederajat</td><td>{{ $rekapKategori['sma'] ?? 0 }}</td></tr>

                    <tr><td>Masjid Besar</td><td>{{ $rekapKategori['masjid_besar'] ?? 0 }}</td></tr>
                    <tr><td>Mushola</td><td>{{ $rekapKategori['mushola'] ?? 0 }}</td></tr>
                    <tr><td>Gereja</td><td>{{ $rekapKategori['gereja'] ?? 0 }}</td></tr>
                    <tr><td>Pasar</td><td>{{ $rekapKategori['pasar'] ?? 0 }}</td></tr>
                    <tr><td>Balai Pertemuan</td><td>{{ $rekapKategori['balai_pertemuan'] ?? 0 }}</td></tr>
                </tbody>
            </table>
        </div> --}}

    </div>
</section>
@endsection
