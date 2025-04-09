@extends('setting.pengaturan_database.index')
@section('content_pengaturan_database')
    {{-- <p class="lead">Backup yang dihasilkan akan tersimpan di storage aplikasi. </p>   --}}

    {{-- button backup --}}
    <button onclick="runBackup()" id="btnBackup" type="button" class="btn btn-success btn-sm btn-social" style="margin-bottom: 1rem">
        <i class="fa fa-plus"></i>Buat Cadangan Baru Database
    </button>

    {{-- message --}}
    <div id="restoreMessage" style="margin-bottom: 15px;"></div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="data-backup-database" style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama File</th>
                    <th>Tanggal Dicadangkan</th>
                    <th>Lokasi Penyimpanan</th>
                    <th>Ukuran file</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            // table 
            var data = $('#data-backup-database').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('setting.pengaturan-database.getdata') !!}",
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'path',
                        name: 'path'
                    },
                    {
                        data: 'size',
                        name: 'size'
                    }, {
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [2, 'desc']
                ]
            });

            // runBackup
            runBackup = function() {
                if (confirm('Apakah Anda yakin ingin mencadangkan database ini?')) {

                    let restoreMessage = $('#restoreMessage');
                    let btnBackup = $("#btnBackup");

                    $.ajax({
                        type: 'POST',
                        url: "{!! route('setting.pengaturan-database.runbackup') !!}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function() {
                            restoreMessage.html('<p>Processing, please wait...</p>');
                            btnBackup.removeClass("btn-social");
                            btnBackup.attr("disabled", true);
                            btnBackup.html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                        },
                        success: function(response) {
                            $('#data-backup-database').DataTable().ajax.reload();

                            btnBackup.addClass("btn-social");
                            btnBackup.attr("disabled", false);
                            btnBackup.html('<i class="fa fa-plus"></i>Buat Cadangan Baru Database');
                        },
                        error: function(xhr, status, error) {
                            restoreMessage.html('<p class="text-danger">Error: ' + xhr.responseJSON
                                .message + '</p>');

                            btnBackup.addClass("btn-social");
                            btnBackup.attr("disabled", false);
                            btnBackup.html('<i class="fa fa-plus"></i>Buat Cadangan Baru Database');
                        },
                        complete: function() {
                            restoreMessage.html(
                                '<p class="text-success">Berhasil membuat salinan database.</p>'
                            );
                            btnBackup.addClass("btn-social");
                            btnBackup.attr("disabled", false);
                            btnBackup.html('<i class="fa fa-plus"></i>Buat Cadangan Baru Database');
                        }
                    });

                }

                return false;
            }

            // delete backup
            deleteBackup = function(url) {
                if (confirm('Apakah Anda yakin ingin menghapus file backup ini?')) {
                    window.location.href = url;
                }
            }

        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
