<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-body" id="phpinfo-container">
                <div class="text-center">
                    <button type="button" class="btn btn-primary" id="load-phpinfo">
                        <i class="fa fa-refresh"></i> Tampilkan Info Sistem
                    </button>
                </div>
                <div id="phpinfo-content" style="display: none;">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Mengambil data...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#phpinfo-content {
    max-height: 600px;
    overflow-y: auto;
}
#phpinfo-content pre {
    margin: 0;
    padding: 0;
    background: transparent;
    border: none;
}
#phpinfo-content table {
    width: 100%;
    border-collapse: collapse;
}
#phpinfo-content table td, 
#phpinfo-content table th {
    padding: 5px 10px;
    border: 1px solid #ddd;
}
#phpinfo-content .e {
    background-color: #f0f0f0;
    font-weight: bold;
}
#phpinfo-content .h {
    background-color: #e8f0fe;
}
#phpinfo-content center {
    display: none;
}
</style>

@push('scripts')
<script>
$(document).on('click', '#load-phpinfo', function() {
    var btn = $(this);
    var container = $('#phpinfo-content');
    
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memuat...');
    
    $.ajax({
        url: '{{ route("setting.info-sistem.phpinfo") }}',
        type: 'GET',
        success: function(response) {
            container.show().html(response);
            btn.hide();
            
            container.find('table').addClass('table table-bordered');
        },
        error: function(xhr) {
            container.show().html('<div class="alert alert-danger">Gagal memuat info sistem</div>');
            btn.prop('disabled', false).html('<i class="fa fa-refresh"></i> Tampilkan Info Sistem');
        }
    });
});
</script>
@endpush
