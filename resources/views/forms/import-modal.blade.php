<div id="active-modal" class="modal fade modal-danger in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Import XML</h4>
            </div>
            {!! html()->form('POST')->route('admin.news.importxml')->id('active')->acceptsFiles()->open() !!}

            <div class="modal-body">
                {!! html()->file('file')->class('form-control')->accept('text/xml') !!}
                <a href="{{ route('admin.news.download') }}">Download example xml</a>
            </div>

            <div class="modal-footer">

                <a id="active-modal-cancel" href="#" class="btn btn-danger waves-effect waves-light"
                    data-dismiss="modal">Cancel</a>

                {!! html()->submit('Import')->class('btn btn-primary waves-effect waves-light') !!}

            </div>
            {!! html()->form()->close() !!}
            @push('scripts')
            {!! JsValidator::formRequest('App\Http\Requests\Backend\ImportNewsRequest', '#active') !!}
            @endpush
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $(document).on('click', '#importxml', function(e) {
            $('#active-modal').modal('show');
            e.preventDefault();
        });
    });
</script>