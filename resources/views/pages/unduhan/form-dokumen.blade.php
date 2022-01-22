@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">Data Dokumen Formulir </h4>
        </div>
        <!-- /.box-header -->
        
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="dokumen-table">
                    <thead>
                        <tr>
                            <th style="max-width: 160px;">Aksi</th>
                            <th>Nama Dokumen</th>
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
    $(document).ready(function () {
        var data = $('#dokumen-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: "{!! route( 'unduhan.form-dokumen.getdata' ) !!}",
            columns: [
                {data: 'aksi', name: 'aksi', class: 'text-center', searchable: false, orderable: false},
                {data: 'nama_dokumen', name: 'nama_dokumen'},
            ],
            order: [[1, 'desc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@endpush