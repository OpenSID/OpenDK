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
                <button type="button" id="export-excel-btn" class="btn btn-primary btn-sm btn-social" title="Export Excel">
                    <i class="fa fa-download"></i>Export Excel
                </button>
            </a>
        </div>

        <div class="box-body">
            @include('layouts.fragments.list-desa')
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="datadesa-table">
                    <thead>
                        <tr>
                            <th style="max-width: 150px;">Aksi</th>
                            <th>Foto</th>
                            <th>NO KK</th>
                            <th>Nama Kepala</th>
                            <th>Tanggal Daftar</th>
                            <th>Tanggal Cetak KK</th>
                            <th>Desa</th>
                            <th>Alamat</th>
                            <th>RW</th>
                            <th>RT</th>
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
            $('#list_desa').select2();

            var data = $('#datadesa-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/keluarga?' .
                        http_build_query([
                            'filter[kode_kecamatan]' => str_replace('.', '', $profil->kecamatan_id),
                        ]) }}`,
                    headers: {
                        "Accept": "application/ld+json",
                        "Content-Type": "text/json; charset=utf-8",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    method: 'get',
                    data: function(row) {

                        var selectedDesa = $('#list_desa').val();
                        var searchValue = row.search.value;

                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": searchValue,
                            "filter[kode_desa]": selectedDesa == 'Semua' ? '' : selectedDesa,
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

                            const _url = `{{ url('data/keluarga/show') }}/${data.id}`

                            return `<a href="${_url}" title="Lihat" data-button="show">
                                <button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye fa-fw"></i></button>
                            </a>`;
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'attributes.foto',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {

                            const url = getImageUrl(data, row.attributes.sex);

                            return `<img src="${url}" class="img-rounded" alt="Foto Penduduk" height="50"/>`;
                        }
                    },
                    {
                        data: 'attributes.no_kk',
                        render: function(data) {
                            return data ? data : '<span class="text-muted">Tidak Ada NO KK</span>';
                        }
                    },
                    {
                        data: 'attributes.nama_kk',
                    },
                    {
                        data: 'attributes.tgl_daftar',
                    },
                    {
                        data: 'attributes.tgl_cetak_kk',
                    },
                    {
                        data: 'attributes.desa',
                    },
                    {
                        data: 'attributes.alamat_plus_dusun',
                    },
                    {
                        data: 'attributes.rt',
                    },
                    {
                        data: 'attributes.rw',
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
                // e.preventDefault();
                // var desa = $('#list_desa').val();
                // var exportUrl = "{{ route('data.keluarga.export-excel') }}";
                
                // if (desa && desa !== 'Semua') {
                //     exportUrl += '?desa=' + encodeURIComponent(desa);
                // }
                
                // window.location.href = exportUrl;
                downloadExcel();

            });

            // Function to download Excel
            async function downloadExcel() {
                try {
                    const header = @include('components.header_bearer_api_gabungan');
                    // Check if there's data to download
                    const tableData = $('#datadesa-table').DataTable();
                    const info = tableData.page.info();
                    const totalData = info.recordsTotal;
                    if (totalData === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Ada Data',
                            text: 'Tidak ada data penduduk untuk diunduh. Silakan periksa filter Anda.',
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
                        `{{ $settings['api_server_database_gabungan'] }}/api/v1/keluarga/download`);

                    // Gunakan fungsi data yang sama persis dengan DataTable untuk konsistensi
                    const filterParams = tableData.ajax.params();
                    console.log(filterParams);


                    // Remove pagination parameters since we want all data
                    delete filterParams['page[size]'];
                    delete filterParams['page[number]'];

                    // Handle umur filter - convert object to separate min/max parameters for backend
                    if (filterParams['filter[umur]'] && typeof filterParams['filter[umur]'] === 'object') {
                        const umurObj = filterParams['filter[umur]'];

                        // Create separate parameters for min and max
                        if (umurObj.min && umurObj.min !== '') {
                            filterParams['filter[umur][min]'] = umurObj.min;
                        }
                        if (umurObj.max && umurObj.max !== '') {
                            filterParams['filter[umur][max]'] = umurObj.max;
                        }
                        if (umurObj.satuan) {
                            filterParams['filter[umur][satuan]'] = umurObj.satuan;
                        }

                        // Remove the original object parameter
                        delete filterParams['filter[umur]'];
                    }

                    // Convert filterParams to URLSearchParams for proper encoding
                    const urlParams = new URLSearchParams();
                    Object.keys(filterParams).forEach(key => {
                        const value = filterParams[key];
                        if (value !== null && value !== undefined && value !== '' && value !== 'null') {
                            urlParams.append(key, value);
                        }
                    });

                    urlParams.append('totalData', totalData);

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
                    let filename = 'data_penduduk.xlsx';
                    if (contentDisposition) {
                        const matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(contentDisposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    } else {
                        // Generate filename with timestamp
                        const now = new Date();
                        const timestamp = now.toISOString().slice(0, 19).replace(/[-:T]/g, '');
                        filename = `data_penduduk_${timestamp}.xlsx`;
                    }

                    // Create blob and download
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: `File Excel "${filename}" berhasil diunduh`,
                        timer: 3000,
                        showConfirmButton: false
                    });

                } catch (error) {
                    console.error('Download error:', error);

                    // Show error message with SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Download!',
                        html: `
                                <p>Terjadi kesalahan saat mengunduh file Excel:</p>
                                <p><small>${error.message}</small></p>
                                <p>Silakan coba lagi atau hubungi administrator.</p>
                            `,
                        confirmButtonText: 'OK'
                    });
                } finally {
                    // Reset button state
                    const $btnExcel = $('#export-excel-btn');
                    $btnExcel.prop('disabled', false).html('<i class="fa fa-download"></i>Export Excel');
                }
            }

            // Fungsi untuk menentukan URL gambar
            function getImageUrl(data, sex) {
                if (data !== null) {
                    return data; // Jika data tersedia, gunakan data sebagai URL
                }
                // Jika data kosong, gunakan gambar default berdasarkan jenis kelamin
                return sex == 2 ?
                    `{{ asset('img/pengguna/wuser.png') }}` :
                    `{{ asset('img/pengguna/kuser.png') }}`;
            }



        });
</script>
@include('forms.datatable-vertical')
@endpush