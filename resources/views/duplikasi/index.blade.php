@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            Duplikasi Data
            <small>Duplikasi data kecamatan ke kecamatan lain</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('setting.pengaturan-database.backup') }}">Pengaturan</a></li>
            <li class="active">Duplikasi Data</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        <div class="card">
            <div class="card-header">
                <h4>Duplikasi Data ke Kecamatan Lain</h4>
            </div>
            <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="ms-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('duplikasi.process') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="id" class="form-label">ID Kecamatan Tujuan</label>
                            <input type="number" class="form-control @error('id') is-invalid @enderror" placeholder="Kosongkan jika id belum diketahui"
                                   id="id" name="id" value="{{ old('id') }}" >
                            @error('id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">ID kecamatan tujuan (opsional)</div>
                        </div>                        
                        
                        <div class="mb-3">
                            <label for="id_start_range" class="form-label">ID Range Awal (Target)</label>
                            <input type="number" class="form-control @error('id_start_range') is-invalid @enderror"
                                   id="id_start_range" name="id_start_range" value="{{ old('id_start_range', 1) }}" min="1" required>
                            @error('id_start_range')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">ID awal untuk penempatan data di kecamatan tujuan</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="id_end_range" class="form-label">ID Range Akhir (Target)</label>
                            <input type="number" class="form-control @error('id_end_range') is-invalid @enderror"
                                   id="id_end_range" name="id_end_range" value="{{ old('id_end_range', 1000) }}" min="1" required>
                            @error('id_end_range')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">ID akhir untuk penempatan data di kecamatan tujuan</div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <strong>Perhatian:</strong> Proses duplikasi akan menyalin sejumlah data dari kecamatan saat ini
                            ke kecamatan tujuan. Jumlah data yang disalin dihitung dari rentang ID yang ditentukan.
                            Data pertama akan ditempatkan di ID sesuai dengan ID Range Awal yang ditentukan.
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Duplikasi Data</button>                        
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Add any JavaScript if needed
        });
    </script>
@endpush