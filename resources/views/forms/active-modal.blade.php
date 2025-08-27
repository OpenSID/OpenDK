<div id="active-modal" class="modal fade modal-info in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Konfirmasi</h4>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin mengaktifkan data ini?</p>
            </div>
            <div class="modal-footer">
                {!! Html::form('POST')->id('active')->open() !!}
                <a id="active-modal-cancel" href="#" class="btn btn-danger pull-left" data-dismiss="modal">Batal</a>
                {!! Html::submit('Aktifkan')->class('btn btn-success') !!}
                {!! Html::form()->close() !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $(document).on('click', '#activeModal', function(e) {
            var url = $(this).attr('data-href');
            $('#active').attr('action', url);
            $('#import').attr('method', 'post');
            $('#active-modal').modal('show');
            e.preventDefault();
        });
    });
</script>