@extends('layouts.app')

@section('content')
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">DAFTAR POTENSI</h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="potensi-table">
                        <thead>
                            <tr>
                                <th style="max-width: 150px;">Aksi</th>
                                <th>Gambar</th>
                                <th>Nama Potensi</th>
                                <th>Deskripsi</th>
                                <th>Lokasi</th>
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
            var table = $('#potensi-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '{!! $urlApi !!}/potensi',
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
                columns: [
                    {
                        data: null,
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            return '<button class="btn btn-xs btn-primary" onclick="showPotensiDetail(\'' + row.id + '\')"><i class="fa fa-eye"></i> Detail</button>';
                        }
                    },
                    {
                        data: null,
                        name: 'file_gambar_path',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            var imagePath = row.attributes.file_gambar_path || '{{ asset("/img/no-image.png") }}';
                            return '<img src="' + imagePath + '" alt="' + (row.attributes.nama_potensi || '') + '" style="max-width: 80px; max-height: 60px;">';
                        }
                    },
                    {
                        data: 'attributes.nama_potensi',
                        name: 'nama_potensi'
                    },
                    {
                        data: 'attributes.deskripsi',
                        name: 'deskripsi',
                        render: function(data, type, row) {
                            if (data && data.length > 100) {
                                return data.substring(0, 100) + '...';
                            }
                            return data || '';
                        }
                    },
                    {
                        data: 'attributes.lokasi',
                        name: 'lokasi'
                    }
                ],
                order: [
                    [2, 'asc']
                ],
                language: {
                    url: '{{ asset("plugins/datatables/id.json") }}'
                }
            });
        });

        // Function to show potensi detail in modal
        function showPotensiDetail(id) {
            $.ajax({
                url: '{!! $urlApi !!}/potensi?filter[id]=' + id,
                method: 'GET',
                success: function(response) {
                    if (response.data && response.data.length > 0) {
                        var potensi = response.data[0].attributes;
                        
                        // Create modal content
                        var modalHtml = '<div class="modal fade" id="potensiDetailModal" tabindex="-1" role="dialog">' +
                            '<div class="modal-dialog modal-lg" role="document">' +
                                '<div class="modal-content">' +
                                    '<div class="modal-header">' +
                                        '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                                        '<h4 class="modal-title">' + (potensi.nama_potensi || '') + '</h4>' +
                                    '</div>' +
                                    '<div class="modal-body">' +
                                        '<div class="row">' +
                                            '<div class="col-md-12">' +
                                                '<img src="' + (potensi.file_gambar_path || "{{ asset('/img/no-image.png') }}") + '" class="img-responsive" style="max-width: 100%; height: auto; margin-bottom: 15px;">' +
                                            '</div>' +
                                            '<div class="col-md-12">' +
                                                '<h5>Deskripsi:</h5>' +
                                                '<p>' + (potensi.deskripsi || '') + '</p>' +
                                                '<h5>Lokasi:</h5>' +
                                                '<p>' + (potensi.lokasi || '') + '</p>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="modal-footer">' +
                                        '<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                        
                        // Remove any existing modal
                        $('#potensiDetailModal').remove();
                        
                        // Add modal to body and show it
                        $('body').append(modalHtml);
                        $('#potensiDetailModal').modal('show');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Gagal memuat detail potensi. Silakan coba lagi.');
                }
            });
        }
    </script>
    @include('forms.datatable-vertical')
@endpush
