<div id="suspend-modal" class="modal fade modal-info in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Konfirmasi</h4>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menangguhkan data ini?</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(['id' => 'suspend', 'method' => 'DELETE']) !!}
                    <a id="suspend-modal-cancel" href="#" class="btn btn-danger pull-left" data-dismiss="modal">Batal</a>
                    {!! Form::submit('Tangguhkan', ['class' => 'btn btn-warning']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $(document).on('click', '#suspendModal', function(e) {
            var url = $(this).attr('data-href');
            $('#suspend').attr('action', url );
            $('#import').attr( 'method', 'post' );
            $('#suspend-modal').modal('show');
            e.preventDefault();
        });
    });
</script>
