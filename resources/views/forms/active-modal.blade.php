<div id="active-modal" class="modal fade modal-danger in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Active {{ $title }} Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure want to active this data?</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(['id' => 'active', 'method' => 'POST']) !!}
                    <a id="active-modal-cancel" href="#" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Cancel</a>
                    {!! Form::submit('Active', [ 'class' => 'btn btn-warning waves-effect waves-light' ]) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

         $(document).on('click', '#activeModal', function(e) {
            var url = $(this).attr('data-href');
            $('#active').attr('aksi', url );
            $('#import').attr( 'method', 'post' );
            $('#active-modal').modal('show');
            e.preventDefault();
        });
    });
</script>
