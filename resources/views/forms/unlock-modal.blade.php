<div id="unlock-modal" class="modal fade modal-info in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Konfirmasi</h4>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menonaktifkan data ini?</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(['id' => 'unlock', 'method' => 'PUT']) !!}
                <a id="active-modal-cancel" href="#" class="btn btn-danger pull-left" data-dismiss="modal">Batal</a>
                {!! Form::submit('Nonaktifkan', ['class' => 'btn btn-success']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $(document).on('click', '#unlockModal', function(e) {
            var url = $(this).attr('data-href');
            $('#unlock').attr('action', url);
            $('#unlock-modal').modal('show');
            e.preventDefault();
        });
    });
</script>
