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
                {{ Form::select('status', ['DITOLAK' => 'Ditolak', 'REVIEW' => 'Review', 'PROSES' => 'Proses', 'SELESAI' => 'Selesai'], null, ['class' => 'form-control']) }}
            </div>
            <div class="modal-footer">
                <a id="active-modal-cancel" href="#" class="btn btn-default waves-effect waves-light" data-dismiss="modal">Batal</a>
                {!! Form::submit('Simpan', ['class' => 'btn btn-warning waves-effect waves-light']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var _trSelected;
        $(document).on('click', '#agreeModal', function(e) {
            var url = $(this).attr('data-href');
            _trSelected = $(this).closest('tr');
            $('#agree').attr('action', url);
            $('#import').attr('method', 'post');
            $('#agree-modal').modal('show');
            e.preventDefault();
        })

        $('#agree-modal').on('show.bs.modal', function(e) {
            $(this).find('select').val(_trSelected.find('td.status').text());
        });
    });
</script>
