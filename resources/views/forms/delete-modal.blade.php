<div id="delete-modal" class="modal fade modal-danger in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Konfirmasi Hapus!</h4>
            </div>
            <div class="modal-body">
                <p>Anda yakin akan menghapus data berikut?</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(['id' => 'destroy', 'method' => 'DELETE']) !!}
                    <a id="delete-modal-cancel" href="#" class="btn btn-default waves-effect waves-light" data-dismiss="modal">Batal</a>
                    {!! Form::submit('Hapus', [ 'class' => 'btn btn-warning waves-effect waves-light' ]) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(document).on('click', '#deleteModal', function(e) {
            var url = $(this).attr('data-href');
            $('#destroy').attr('action', url );
            $('#import').attr( 'method', 'delete' );
            $('#delete-modal').modal('show');
            e.preventDefault();
        });
    });
</script>
