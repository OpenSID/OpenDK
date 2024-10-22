<div id="lock-modal" class="modal fade modal-info in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Konfirmasi</h4>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin mengaktifkan data ini?</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(['id' => 'lock', 'method' => 'PUT']) !!}
                <a id="active-modal-cancel" href="#" class="btn btn-danger pull-left" data-dismiss="modal">Batal</a>
                {!! Form::submit('Aktif', ['class' => 'btn btn-success']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $(document).on('click', '#lockModal', function(e) {
            var url = $(this).attr('data-href');
            $('#lock').attr('action', url);
            $('#lock-modal').modal('show');
            e.preventDefault();
        });
    });
</script>
