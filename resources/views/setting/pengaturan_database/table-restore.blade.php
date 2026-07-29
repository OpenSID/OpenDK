@extends('setting.pengaturan_database.index')
@section('content_pengaturan_database')
    <form id="restoreDatabaseForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="backupFile">File Backup</label>
            <input type="file" id="backupFile" class="form-control" name="backupFile" accept=".zip" required>
            <p class="help-block">Unggah file backup (.zip) yang dihasilkan oleh sistem backup</p>
            <div class="callout callout-warning" style="margin-top: 10px;">
                <p><strong>Informasi:</strong></p>
                <ul style="margin-bottom: 0;">
                    <li>Hanya file <strong>.zip</strong> dari sistem backup yang diterima</li>
                    <li>File <strong>.zip</strong> memulihkan <strong>database + file asset</strong> (foto, dokumen, dll) sekaligus</li>
                </ul>
                <p style="margin-top: 8px; margin-bottom: 0;"><strong>Peringatan:</strong> Restore akan menimpa database dan file yang ada di storage. Pastikan data penting sudah dicadangkan sebelum melanjutkan.</p>
            </div>
            <button type="submit" class="btn btn-primary btn-sm" id="btnSubmit" style="margin-top: 10px;">
                <i class="fa fa-refresh"></i> Restore
            </button>
        </div>
    </form>

    <div id="restoreMessage" style="margin-top: 15px;"></div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#restoreDatabaseForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let restoreMessage = $('#restoreMessage');
                restoreMessage.html('<p>Processing, please wait...</p>');

                let buttonSubmit = $('#btnSubmit');
                buttonSubmit.attr("disabled", true)

                $.ajax({
                    url: "{!! route('setting.pengaturan-database.runrestore') !!}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        restoreMessage.html('<p class="text-success">' + response.message + '</p>');
                        buttonSubmit.attr("disabled", false)
                        $('#restoreDatabaseForm')[0].reset();
                    },
                    error: function(xhr) {
                        let errorMsg = (xhr.responseJSON && xhr.responseJSON.message) ?
                            xhr.responseJSON.message :
                            'Terjadi kesalahan server. Silakan cek log aplikasi.';
                        restoreMessage.html('<p class="text-danger">Error: ' + errorMsg + '</p>');
                        buttonSubmit.attr("disabled", false)
                        $('#restoreDatabaseForm')[0].reset();
                    }
                });
            });
        });
    </script>
@endpush
