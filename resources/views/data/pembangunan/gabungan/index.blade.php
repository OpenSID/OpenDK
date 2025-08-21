@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>
<section class="content container-fluid">
    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            <a href="#">
                <button type="button" id="export-excel-btn" class="btn btn-primary btn-sm btn-social"
                    title="Export Excel">
                    <i class="fa fa-download"></i>Export Excel
                </button>
            </a>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-3">
                    <label>{{ config('setting.sebutan_desa') }}</label>
                    <select class="form-control" id="list_desa">
                        <option value="">Semua {{ config('setting.sebutan_desa') }}</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable" id="pembangunan-table">
                    <thead>
                        <tr>
                            <th>Aksi</th>
                            <th>Nama Kegiatan</th>
                            <th>Sumber Dana</th>
                            <th>Anggaran</th>
                            <th>Volume</th>
                            <th>Tahun</th>
                            <th>Pelaksana</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
@include('partials.asset_sweetalert')
@include('partials.asset_select2')
@include('partials.asset_datatables')

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
            function loadDesa() {
                $.ajax({
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}/api/v1/opendk/desa/{{ str_replace('.', '', $profil->kecamatan_id) }}`,
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    success: function(response) {
                        $('#list_desa').empty().append(
                            '<option value="">Semua {{ config('setting.sebutan_desa') }}</option>');
                        response.data.forEach(desa => {
                            $('#list_desa').append(
                                `<option value="${desa.attributes.kode_desa}">${desa.attributes.nama_desa}</option>`
                            );
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Gagal memuat daftar desa:", error);
                    }
                });
            }

            loadDesa();
            $('#list_desa').select2();

            var data = $('#pembangunan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/opendk/pembangunan?' .
                        http_build_query([
                            'filter[kode_kecamatan]' => str_replace('.', '', $profil->kecamatan_id),
                        ]) }}`,
                    headers: {
                        "Accept": "application/ld+json",
                        "Content-Type": "application/json; charset=utf-8",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    method: 'GET',
                    data: function(row) {
                        const desaId = $('#list_desa').val();
                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[kode_desa]": desaId || '',
                            "filter[search]": row.search.value,
                            "fields[pembangunan]": "*",
                            "sort": (row.order[0]?.dir === "asc" ? "" : "-") + row.columns[row.order[0]
                                ?.column]?.name,
                        };
                    },
                    dataSrc: function(json) {
                        json.recordsTotal = json.meta.pagination.total;
                        json.recordsFiltered = json.meta.pagination.total;

                        return json.data.map(item => ({
                            aksi: `<a href="{{ url('data/pembangunan/rincian') }}/${item.id}/${item.attributes.config.kode_desa}" class="btn btn-primary btn-sm">Detail</a>`,
                            judul: item.attributes.judul,
                            sumber_dana: item.attributes.sumber_dana || 'N/A',
                            anggaran: item.attributes.anggaran || 'N/A',
                            volume: item.attributes.volume || '-',
                            tahun_anggaran: item.attributes.tahun_anggaran || '-',
                            pelaksana_kegiatan: item.attributes.pelaksana_kegiatan || '-',
                            lokasi: item.attributes.lokasi || '-'
                        }));
                    },
                },
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        class: 'text-center'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'sumber_dana',
                        name: 'sumber_dana'
                    },
                    {
                        data: 'anggaran',
                        name: 'anggaran',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'volume',
                        name: 'volume',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tahun_anggaran',
                        name: 'tahun_anggaran',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pelaksana_kegiatan',
                        name: 'pelaksana_kegiatan',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });

            $('#list_desa').on('select2:select', function() {
                data.ajax.reload();
            });

            // Handle export excel with filter
            $('#export-excel-btn').on('click', function(e) {
                downloadExcel();
            });

            // Function to download Excel
            async function downloadExcel() {
                try {
                    const header = @include('components.header_bearer_api_gabungan');
                    // Check if there's data to download
                    const tableData = $('#pembangunan-table').DataTable();
                    const info = tableData.page.info();
                    const totalData = info.recordsTotal;
                    
                    if (totalData === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Ada Data',
                            text: 'Tidak ada data pembangunan desa untuk diunduh. Silakan periksa filter Anda.',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    // Show loading state
                    const $btnExcel = $('#export-excel-btn');
                    $btnExcel.prop('disabled', true).html(
                        '<i class="fa fa-spinner fa-spin"></i> Downloading...');

                    // Prepare URL for download
                    const downloadUrl = new URL(
                        `{{ $settings['api_server_database_gabungan'] }}/api/v1/opendk/pembangunan/download`);

                    // Use same data function as DataTable for consistency
                    const filterParams = tableData.ajax.params();

                    // Remove pagination parameters since we want all data
                    delete filterParams['page[size]'];
                    delete filterParams['page[number]'];

                    // Convert filterParams to URLSearchParams for proper encoding
                    const urlParams = new URLSearchParams();
                    Object.keys(filterParams).forEach(key => {
                        const value = filterParams[key];
                        if (value !== null && value !== undefined && value !== '' && value !== 'null') {
                            urlParams.append(key, value);
                        }
                    });

                    urlParams.append('totalData', totalData);

                    var kode_kecamatan = "{{ str_replace('.', '', config('profil.kecamatan_id')) }}";
                    urlParams.append('kode_kecamatan', kode_kecamatan);

                    // kirim kode_desa dari list_desa
                    var kode_desa = $('#list_desa').val();
                    urlParams.append('kode_desa', kode_desa);

                    // Make fetch request
                    const response = await fetch(downloadUrl, {
                        method: 'POST',
                        headers: {
                            ...header,
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        },
                        body: urlParams
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`HTTP ${response.status}: ${errorText}`);
                    }

                    // Check if response is actually a file
                    const contentType = response.headers.get('content-type');
                    if (!contentType || (!contentType.includes(
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') && !
                            contentType.includes('application/vnd.ms-excel'))) {
                        throw new Error('Response is not a valid Excel file');
                    }

                    // Get filename from response headers or generate one
                    const contentDisposition = response.headers.get('content-disposition');
                    let filename = 'data_anggaran_desa.xlsx';
                    if (contentDisposition) {
                        const matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(contentDisposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    } else {
                        // Generate filename with timestamp
                        const now = new Date();
                        const timestamp = now.toISOString().slice(0, 19).replace(/[-:T]/g, '');
                        filename = `data_anggaran_desa_${timestamp}.xlsx`;
                    }

                    // Download the file
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);

                    // Success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: `File ${filename} berhasil diunduh.`,
                        timer: 3000,
                        showConfirmButton: false
                    });

                } catch (error) {
                    console.error('Download error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengunduh file: ' + error.message,
                        confirmButtonText: 'OK'
                    });
                } finally {
                    // Reset button state
                    const $btnExcel = $('#export-excel-btn');
                    $btnExcel.prop('disabled', false).html('<i class="fa fa-download"></i>Export Excel');
                }
            }
        });
</script>
@endpush