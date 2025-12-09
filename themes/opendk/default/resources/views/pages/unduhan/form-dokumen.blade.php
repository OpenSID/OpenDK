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
                                <th>Status</th>
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
                            'filter[aktif]' : 1,
                            'filter[is_published]' : 1                            
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
                            var viewBtn = '<a href="' + (row.attributes.file_dokumen_path || '#') + '" title="Lihat" target="_blank">' +
                                '<button type="button" class="btn btn-warning btn-sm" style="width: 40px;"><i class="fa fa-eye fa-fw"></i></button>' +
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
                    },
                    {
                        data: 'attributes.is_published',
                        name: 'is_published',
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<span class="label label-success">Published</span>';
                            } else {
                                return '<span class="label label-warning">Draft</span>';
                            }
                        }
                    }
                ],
                order: [
                    [1, 'desc']
                ],              
            });
        });
    </script>    
@endpush
