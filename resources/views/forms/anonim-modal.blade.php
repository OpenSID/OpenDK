<div id="anonim-modal" class="modal fade modal-danger in">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['id' => 'anonim', 'method' => 'PUT']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Identitas Pelapor</h4>
            </div>
            <div class="modal-body">
                <p>Apakah Anda ingin menampilkan identitas pelapor?</p>
                {{ Form::select('anonim', [0 => 'Tampilkan', 1 => 'Sembunyikan'], null, ['class' => 'form-control']) }}
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
        $(document).on('click', '#anonimModal', function(e) {
            var url = $(this).attr('data-href');
            _trSelected = $(this).closest('tr');
            $('#anonim').attr('action', url);
            $('#import').attr('method', 'post');
            $('#anonim-modal').modal('show');
            e.preventDefault();
        })

        $('#anonim-modal').on('show.bs.modal', function(e) {
            // Ambil teks dari kolom 'anonim'
            var anonimValue = _trSelected.find('td.anonim').text().trim();

            // Konversi teks ke nilai yang sesuai
            var selectValue = (anonimValue === 'Disembunyikan') ? '1' : '0';

            // Set nilai select
            $(this).find('select').val(selectValue);
        });
    });
</script>
