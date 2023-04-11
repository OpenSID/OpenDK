@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">DAFTAR PROSEDUR</h4>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="prosedur-table">
                    <thead>
                    <tr>
                        <th style="max-width: 150px;">Aksi</th>
                        <th>Judul Prosedur</th>
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
    $(document).ready(function () {
        var data = $('#prosedur-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: "{!! route( 'unduhan.prosedur.getdata' ) !!}",
            columns: [
                {data: 'aksi', name: 'aksi', class: 'text-center', searchable: false, orderable: false},
                {data: 'judul_prosedur', name: 'judul_prosedur'},
            ],
            order: [[1, 'desc']]
        });
    });
</script>
@include('forms.datatable-vertical')
@endpush