<div id="agree-modal" class="modal fade modal-danger in">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['id' => 'agree', 'method' => 'PUT']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ubah Status Komplain</h4>
            </div>
            <div class="modal-body">
                <p>Silahkan pilih status di bawah ini?</p>
                {{ Form::select('status', ['BELUM' => 'Setujui', 'DITOLAK' => 'Ditolak'], null, ['class' => 'form-control']) }}
            </div>
            <div class="modal-footer">
                    <a id="active-modal-cancel" href="#" class="btn btn-default waves-effect waves-light" data-dismiss="modal">Batal</a>
                    {!! Form::submit('Simpan', [ 'class' => 'btn btn-warning waves-effect waves-light' ]) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

         $(document).on('click', '#agreeModal', function(e) {
            var url = $(this).attr('data-href');
            $('#agree').attr('action', url );
            $('#import').attr( 'method', 'post' );
            $('#agree-modal').modal('show');
            e.preventDefault();
        });
    });
</script>
