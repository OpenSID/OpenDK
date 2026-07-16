@extends('layouts.app')

@section('content')
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">Data Prosedur</h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="prosedur-table">
                        <thead>
                            <tr>
                                <th>Judul Prosedur</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.box-footer -->
        </div>
    </div>
@endsection
@include('partials.asset_datatables')
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#prosedur-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '{!! $urlApi !!}/prosedur',
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
                        };
                    }
                },
                columns: [{
                        data: 'attributes.judul_prosedur',
                        name: 'judul_prosedur',
                        render: function(data, type, row) {
                            return '<a href="#" onclick="showProsedurDetail(\'' + row.id + '\')">' + (data || '') + '</a>';
                        }
                    },
                    {
                        data: null,
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            var viewBtn = '<a href="#" onclick="showProsedurDetail(\'' + row.id + '\')" title="Lihat">' +
                                '<button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye fa-fw"></i></button>' +
                                '</a>';

                            var downloadBtn = '<a href="' + (row.attributes.path_download || '#') + '" title="Unduh" target="_blank">' +
                                '<button type="button" class="btn btn-info btn-sm" style="width: 40px;"><i class="fa fa-download"></i></button>' +
                                '</a>';

                            return viewBtn + ' ' + downloadBtn;
                        }
                    }
                ],
                order: [
                    [0, 'asc']
                ],
                language: {
                    url: '{{ asset('plugins/datatables/id.json') }}'
                }
            });
        });

        // Function to show prosedur detail in modal
        function showProsedurDetail(id) {
            $.ajax({
                url: '{!! $urlApi !!}/prosedur?filter[id]=' + id,
                method: 'GET',
                success: function(response) {
                    if (response.data && response.data.length > 0) {
                        var prosedur = response.data[0].attributes;
                        let isPdf = (prosedur.mime_type === 'application/pdf' || (prosedur.file_prosedur_path && prosedur.file_prosedur_path.toLowerCase().endsWith('.pdf')));
                        let objFile = !isPdf ? `<img id="fileUnduhan" style="width: auto" src="${prosedur.file_prosedur_path}" width="100%">` : `<iframe src="${prosedur.file_prosedur_path}" width="100%" height="500" class="" id="showpdf" frameborder="0"></iframe>`;
                        // Create modal content
                        var modalHtml = '<div class="modal fade" id="prosedurDetailModal" tabindex="-1" role="dialog">' +
                            '<div class="modal-dialog modal-lg" role="document">' +
                            '<div class="modal-content">' +
                            '<div class="modal-header">' +
                            '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                            '<h4 class="modal-title">' + (prosedur.judul_prosedur || '') + '</h4>' +
                            '</div>' +
                            '<div class="modal-body">' +
                            '<div class="row">' +
                            '<div class="col-md-12 text-center">' +
                            objFile + '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="modal-footer">' +
                            '<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>' +
                            '<a href="' + (prosedur.path_download || '#') + '" class="btn btn-primary" target="_blank">Unduh File</a>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                        // Remove any existing modal
                        $('#prosedurDetailModal').remove();

                        // Add modal to body and show it
                        $('body').append(modalHtml);
                        $('#prosedurDetailModal').modal('show');
                    }
                },
                error: function(xhr, status, error) {
                    openAlert('Gagal memuat detail potensi. Silakan coba lagi.', 'Error', 'danger');
                }
            });
        }
    </script>
@endpush
