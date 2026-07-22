@extends('layouts.app')

@section('content')
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">Data Dokumen Formulir </h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="dokumen-table">
                        <thead>
                            <tr>
                                <th style="max-width: 160px;">Aksi</th>
                                <th>Nama Dokumen</th>
                                <th>Jenis Dokumen</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>
    </div>
@endsection
@include('partials.asset_datatables')
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#dokumen-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '{!! $urlApi !!}/form-dokumen',
                    cache: false,
                    dataSrc: 'data',
                    data: function(d) {
                        // Convert DataTables parameters to API format (use safe defaults to avoid NaN)
                        var start = (typeof d.start !== 'undefined' && d.start !== null) ? d.start : 0;
                        var length = (typeof d.length !== 'undefined' && d.length) ? d.length : (typeof d.pageLength !== 'undefined' ? d.pageLength : 10);
                        var pageNumber = 1;
                        if (length && !isNaN(length)) {
                            pageNumber = Math.floor(start / length) + 1;
                        }

                        return {
                            'page[number]': pageNumber,
                            'page[size]': length,
                            'filter[aktif]': 1,
                            'filter[is_published]': 1
                        };
                    }
                },
                columns: [{
                        data: null,
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            var viewBtn = '<a href="#" onclick="showDokumenDetail(\'' + row.id + '\')" title="Lihat">' +
                                '<button type="button" class="btn btn-warning btn-sm"><i class="fa fa-eye fa-fw"></i> Lihat Dokumen</button>' +
                                '</a>';
                            return viewBtn;
                        }
                    },
                    {
                        data: 'attributes.nama_dokumen',
                        name: 'nama_dokumen'
                    },
                    {
                        data: 'attributes.jenis_dokumen.nama',
                        name: 'jenis_dokumen',
                        defaultContent: '-'
                    }
                ],
                order: [
                    [1, 'desc']
                ],
            });
        });

        function formatRetensi(retention_days) {
            if (!retention_days || retention_days == 0) return 'Berlaku Selamanya';
            if (retention_days % 365 === 0) return (retention_days / 365) + ' Tahun';
            if (retention_days % 30 === 0) return (retention_days / 30) + ' Bulan';
            return retention_days + ' Hari';
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            var d = new Date(dateString);
            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return ('0' + d.getDate()).slice(-2) + ' ' + months[d.getMonth()] + ' ' + d.getFullYear() + ' ' + ('0' + d.getHours()).slice(-2) + ':' + ('0' + d.getMinutes()).slice(-2);
        }

        function showDokumenDetail(id) {
            $.ajax({
                url: '{!! $urlApi !!}/form-dokumen?filter[id]=' + id,
                method: 'GET',
                success: function(response) {
                    if (response.data && response.data.length > 0) {
                        var dokumen = response.data[0].attributes;
                        let isPdf = (dokumen.mime_type === 'application/pdf' || (dokumen.file_dokumen_path && dokumen.file_dokumen_path.toLowerCase().endsWith('.pdf')));
                        let objFile = !isPdf ? `<img id="fileUnduhan" style="max-width: 100%; height: auto;" src="${dokumen.file_dokumen_path}">` : `<iframe src="${dokumen.file_dokumen_path}" width="100%" height="500" class="" id="showpdf" frameborder="0"></iframe>`;

                        var retensiText = formatRetensi(dokumen.retention_days);

                        var modalHtml = '<div class="modal fade" id="dokumenDetailModal" tabindex="-1" role="dialog">' +
                            '<div class="modal-dialog modal-lg" role="document">' +
                            '<div class="modal-content">' +
                            '<div class="modal-header">' +
                            '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                            '<h4 class="modal-title">' + (dokumen.nama_dokumen || '') + '</h4>' +
                            '</div>' +
                            '<div class="modal-body">' +
                            '<div class="row">' +
                            '<div class="col-md-12">' +
                            '<table class="table table-bordered">' +
                            '<tr><th style="width: 25%">Jenis Dokumen</th><td>' + (dokumen.jenis_dokumen.nama || '-') + '</td></tr>' +
                            '<tr><th>Deskripsi</th><td>' + (dokumen.description || '-') + '</td></tr>' +
                            '<tr><th>Tanggal Terbit</th><td>' + formatDate(dokumen.published_at) + '</td></tr>' +
                            '<tr><th>Waktu Retensi</th><td>' + retensiText + '</td></tr>' +
                            '<tr><th>Berlaku Hingga</th><td>' + (dokumen.expired_at ? formatDate(dokumen.expired_at) : 'Selamanya') + '</td></tr>' +
                            '</table>' +
                            '</div>' +
                            '<div class="col-md-12 text-center" style="margin-top: 15px;">' +
                            objFile + '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="modal-footer">' +
                            '<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>' +
                            '<a href="' + (dokumen.file_dokumen_path || '#') + '" class="btn btn-primary" target="_blank"><i class="fa fa-download"></i> Unduh File</a>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                        $('#dokumenDetailModal').remove();
                        $('body').append(modalHtml);
                        $('#dokumenDetailModal').modal('show');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Gagal memuat detail dokumen. Silakan coba lagi.');
                }
            });
        }
    </script>
@endpush
