@extends('layouts.dashboard_template')

@section('content')
    <!-- Content Header (Page header) -->
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
                    <button type="button" id="export-excel-btn" class="btn btn-primary btn-sm btn-social" title="Export Excel">
                        <i class="fa fa-download"></i>Export Excel
                    </button>
                </a>
            </div>
            <div class="box-body">
                @include('layouts.fragments.list-desa')
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="apbdes-table">
                        <thead>
                            <tr>
                                <th style="max-width: 100px;">Aksi</th>
                                <th>{{ config('setting.sebutan_desa') }}</th>
                                <th>Nama</th>
                                <th>Tahun</th>
                                <th>Semester</th>
                                <th>Tgl. Lapor</th>
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
            var data = $('#apbdes-table').DataTable({
                autoWidth: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/keuangan/laporan_apbdes?' .
                        http_build_query([
                            'filter[kode_kecamatan]' => str_replace('.', '', $profil->kecamatan_id),
                        ]) }}`,
                    headers: {
                        "Accept": "application/ld+json",
                        "Content-Type": "text/json; charset=utf-8",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    data: function(row) {
                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": row.search.value,
                            "filter[config_id]": $('#list_desa').val(),
                            "sort": (row.order[0]?.dir === "asc" ? "" : "-") + row.columns[row.order[0]
                                    ?.column]
                                ?.name,
                        };
                    },
                    dataSrc: function(json) {
                        json.recordsTotal = json.meta.pagination.total
                        json.recordsFiltered = json.meta.pagination.total

                        return json.data
                    },
                },
                columns: [{
                        data: function(data) {
                            const _url = data.attributes.url_file;
                            return `<a href="${_url}" title="Unduh" data-button="download" target="_blank">
                                <button type="button" class="btn btn-info btn-sm">download</button>
                            </a>`;
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'attributes.nama_desa',
                        name: 'config.nama_desa',
                        defaultContent: '-'
                    },
                    {
                        data: 'attributes.judul',
                        name: 'judul'
                    },
                    {
                        data: 'attributes.tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'attributes.semester',
                        name: 'semester'
                    },
                    {
                        data: 'attributes.created_at_local',
                        name: 'created_at',
                        searchable: false,
                    },

                ],
                order: [
                    [1, 'asc']
                ]
            });

            $('#list_desa').on('select2:select', function(e) {
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
                    const tableData = $('#apbdes-table').DataTable();
                    const info = tableData.page.info();
                    const totalData = info.recordsTotal;

                    if (totalData === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Ada Data',
                            text: 'Tidak ada data laporan APBDes untuk diunduh. Silakan periksa filter Anda.',
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
                        `{{ $settings['api_server_database_gabungan'] }}/api/v1/keuangan/laporan_apbdes/download`);

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

                    // kirim kode_desa ke urlParams dari list_desa
                    const kode_desa = $('#list_desa').val();
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
                    let filename = 'data_laporan_apbdes.xlsx';
                    if (contentDisposition) {
                        const matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(contentDisposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    } else {
                        // Generate filename with timestamp
                        const now = new Date();
                        const timestamp = now.toISOString().slice(0, 19).replace(/[-:T]/g, '');
                        filename = `data_laporan_apbdes_${timestamp}.xlsx`;
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
    @include('forms.datatable-vertical')
@endpush
