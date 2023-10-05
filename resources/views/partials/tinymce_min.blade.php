@include('partials.asset_tinymce')

@push('scripts')
    <script>
        tinymce.init({
            selector: 'textarea.my-editor',
            promotion: false,
            theme: 'silver',
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons paste textcolor code"
            ],
            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
            toolbar2: "| link unlink anchor | image media | forecolor backcolor | print preview code | fontselect fontsizeselect",
            image_advtab: true,
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ],
            skin: 'tinymce-5',
            relative_urls: false,
            remove_script_host: false
        });
    </script>
@endpush
