@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{!! $page_title !!}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        @if ($jenis_dokumen->count() > 0)
            <div class="box box-primary">
                <div class="box-header with-border">
                    @include('forms.btn-social', ['create_url' => route('informasi.form-dokumen.create')])
                </div>

                <div class="box-body">
                    <!-- Filter Dropdown untuk bulan, tahun, dan tipe dokumen -->
                    <div class="form-group row">
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <div class="row">
                                {{-- Filter Bulan --}}
                                <div class="col-md-4 pl-md-1">
                                    {!! Form::select(
                                        'bulan',
                                        [
                                            '01' => 'Januari',
                                            '02' => 'Februari',
                                            '03' => 'Maret',
                                            '04' => 'April',
                                            '05' => 'Mei',
                                            '06' => 'Juni',
                                            '07' => 'Juli',
                                            '08' => 'Agustus',
                                            '09' => 'September',
                                            '10' => 'Oktober',
                                            '11' => 'November',
                                            '12' => 'Desember',
                                        ],
                                        null,
                                        [
                                            'class' => 'form-control',
                                            'placeholder' => '- Pilih Bulan -',
                                        ],
                                    ) !!}
                                </div>

                                {{-- Filter Tahun --}}
                                <div class="col-md-4 pr-md-1">
                                    {!! Form::select('tahun', array_combine(range(date('Y'), date('Y') - 10), range(date('Y'), date('Y') - 10)), null, [
                                        'class' => 'form-control',
                                        'placeholder' => '- Pilih Tahun -',
                                    ]) !!}
                                </div>

                                {{-- Filter Jenis Dokumen --}}
                                <div class="col-md-4 pr-md-1">
                                    {!! Form::select('jenis_dokumen_id', \App\Models\JenisDokumen::pluck('nama', 'id'), null, [
                                        'placeholder' => '-Pilih Jenis Dokumen-',
                                        'class' => 'form-control',
                                        'id' => 'jenis_dokumen_id',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="dokumen-table">
                            <thead>
                                <tr>
                                    <th class="text-center text-nowrap" style="max-width: 160px;">Aksi</th>
                                    <th>Jenis Dokumen</th>
                                    <th>Judul Dokumen</th>
                                    <th>Waktu Retensi Dokumen</th>
                                    <th>Tanggal Terbit</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="callout callout-warning">
                <h4>Informasi!</h4>
                <p>Data jenis dokumen belum tersedia. Silahkan tambah data <b><a href="{{ route('setting.jenis-dokumen.index') }}">jenis dokumen</a></b> terlebih dahulu.</p>
            </div>
        @endif
    </section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#dokumen-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('informasi.form-dokumen.getdata') !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center text-nowrap',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'jenis_dokumen',
                        name: 'jenis_dokumen',
                        render: function(data, type, row) {
                            if (row.jenis_dokumen != '-') {
                                return `<span class="label label-warning">${row.jenis_dokumen}</span>`
                            } else {
                                return row.jenis_dokumen
                            }
                        }
                    },
                    {
                        data: 'nama_dokumen',
                        name: 'nama_dokumen'
                    },
                    {
                        data: 'expired_at',
                        name: 'expired_at'
                    },
                    {
                        data: 'published_at',
                        name: 'published_at'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: null,
                        name: 'status',
                        render: function(data, type, row) {
                            const isPublished = row.is_published;
                            const expiredAt = row.expired_at;
                            const today = new Date();
                            today.setHours(0, 0, 0,
                                0); // Hilangkan waktu agar perbandingan lebih akurat
                            let status = '';
                            let badgeClass = '';

                            if (!isPublished) {
                                status = 'Draft';
                                badgeClass = 'label-warning';
                            } else if (!expiredAt || expiredAt.toLowerCase() == 'selamanya') {
                                status = 'Terbit';
                                badgeClass = 'label-success';
                            } else {
                                const expiredDate = new Date(expiredAt);
                                expiredDate.setHours(0, 0, 0, 0); // Hilangkan waktu

                                if (expiredDate >= today) {
                                    status = 'Terbit';
                                    badgeClass = 'label-success';
                                } else {
                                    status = 'Kadaluwarsa';
                                    badgeClass = 'label-danger';
                                }
                            }

                            return `<span class="label ${badgeClass}">${status}</span>`;
                        }
                    },
                ],
                order: [
                    [1, 'desc']
                ]
            });

            var bulan = '';
            var tahun = '';
            var jenisDokumenId = '';

            $('select[name="bulan"]').on('change', function() {
                bulan = $(this).val(); // Ambil value yang dipilih
                console.log(bulan)

                // Lakukan request dengan filter status yang dipilih
                table.ajax.url('{!! route('informasi.form-dokumen.getdata') !!}?bulan=' + bulan + '&tahun=' + tahun +
                    '&jenis_dokumen_id=' + jenisDokumenId).load();
            });

            $('select[name="tahun"]').on('change', function() {
                tahun = $(this).val(); // Ambil value yang dipilih

                // Lakukan request dengan filter status yang dipilih
                table.ajax.url('{!! route('informasi.form-dokumen.getdata') !!}?bulan=' + bulan + '&tahun=' + tahun +
                    '&jenis_dokumen_id=' + jenisDokumenId).load();
            });

            $('select[name="jenis_dokumen_id"]').on('change', function() {
                jenisDokumenId = $(this).val(); // Ambil value yang dipilih

                // Lakukan request dengan filter status yang dipilih
                table.ajax.url('{!! route('informasi.form-dokumen.getdata') !!}?bulan=' + bulan + '&tahun=' + tahun +
                    '&jenis_dokumen_id=' + jenisDokumenId).load();
            });

        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
