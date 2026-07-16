<div class="row">
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <a href="{{ route('informasi.artikel.index') }}"><button type="button" class="btn btn-info btn-sm"><i class="fa fa-arrow-left"></i> Kembali</button></a>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label" for="judul">Judul Artikel</label>

                    {!! html()->text('judul')->class('form-control')->placeholder('Judul Artikel')->value(old('judul', isset($artikel) ? $artikel->judul : ''))->required() !!}
                    @if ($errors->has('judul'))
                        <span class="help-block" style="color:red">{{ $errors->first('judul') }}</span>
                    @endif
                    <!-- <span class="help-block"><code>Judul minimal 5 karakter dan maksimal 100 karakter</code></span> -->
                </div>

                <div class="form-group">
                    <label class="control-label" for="isi">Isi Artikel</label>

                    {!! html()->textarea('isi')->class('form-control my-editor')->placeholder('Isi Artikel')->style(
                            'width:100%; height:750px; font-size:14px; line-height:18px; border:1px solid #dddddd;
                                                                                                                                                                                                                            padding:10px;',
                        )->value(old('isi', isset($artikel) ? $artikel->isi : '')) !!}
                    @if ($errors->has('isi'))
                        <span class="help-block" style="color:red">{{ $errors->first('isi') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label" for="gambar">Gambar</label>

                    @if (isset($artikel) && $artikel->gambar)
                        <div style="padding:8px; background:#f5f5f5; border-radius:4px; margin-bottom:8px; display:flex; align-items:center; gap:10px;">
                            <img src="{{ $artikel->gambar }}"
                                 style="max-height:60px; max-width:90px; object-fit:contain; border-radius:3px;"
                                 class="img-thumbnail">
                            <div style="overflow: hidden;">
                                <span class="text-muted" style="word-break: break-all;">{{ basename($artikel->gambar) }}</span>
                            </div>
                        </div>
                        <small class="help-block text-muted">
                            Upload gambar baru di bawah untuk menggantikan gambar yang ada.
                        </small>
                    @else
                        <img class="hide" id="showgambar" style="width:100%; max-height:250px; float:left;" />
                    @endif

                    {!! html()->file('gambar')->class('form-control')->id('file-artikel')->accept('.jpg,.jpeg,.png') !!}
                    <x-upload-hint formats="JPG, JPEG, PNG" />
                    @if ($errors->has('gambar'))
                        <span class="help-block" style="color:red">{{ $errors->first('gambar') }}</span>
                    @endif

                    @if (isset($artikel) && $artikel->gambar)
                        <div class="clearfix"></div>
                        <br>
                        <img class="hide" id="showgambar" style="width:100%; max-height:250px; float:left;" />
                    @endif
                </div>

                <!-- kategori artikel -->
                <div class="form-group">
                    <label class="control-label" for="gambar">Kategori</label>

                    {!! html()->select('id_kategori', $kategori)->class('form-control')->value(old('id_kategori', isset($artikel) ? $artikel->id_kategori : '')) !!}
                    @if ($errors->has('id_kategori'))
                        <span class="help-block" style="color:red">{{ $errors->first('id_kategori') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="control-label" for="tanggal_terbit">Tanggal Terbit</label>

                    {!! html()->date('tanggal_terbit')->class('form-control')->value(old('tanggal_terbit', isset($artikel) ? $artikel->tanggal_terbit->format('Y-m-d') : ''))->required() !!}
                    @if ($errors->has('tanggal_terbit'))
                        <span class="help-block" style="color:red">{{ $errors->first('tanggal_terbit') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="control-label" for="gambar">Status</label>

                    {!! html()->select('status', ['0' => 'Tidak Aktif', '1' => 'Aktif'])->value(old('status', isset($artikel) ? $artikel->status : ''))->class('form-control') !!}
                    @if ($errors->has('status'))
                        <span class="help-block" style="color:red">{{ $errors->first('status') }}</span>
                    @endif
                </div>
            </div>

            <div class="box-footer">
                @include('partials.button_reset_submit')
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.11/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        $(function() {

            var fileTypes = ['jpg', 'jpeg', 'png']; //acceptable file types

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var extension = input.files[0].name.split('.').pop()
                        .toLowerCase(), //file extension from input file
                        isSuccess = fileTypes.indexOf(extension) > -1; //is extension in acceptable types

                    if (isSuccess) { //yes
                        var reader = new FileReader();
                        reader.onload = function(e) {

                            $('#showgambar').attr('src', e.target.result);
                            $('#showgambar').removeClass('hide');

                        }

                        reader.readAsDataURL(input.files[0]);
                    } else { //no
                        //warning
                        $("#file-artikel").val('');
                        openAlert('File tersebut tidak diperbolehkan.', 'Peringatan', 'warning');
                    }
                }
            }

            $("#file-artikel").change(function() {
                readURL(this);
            });
        });

        var editor_config = {
            path_absolute: "/",
            selector: "textarea.my-editor",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link gambar media",
            relative_urls: false,
            image_caption: true,
            file_browser_callback: function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;
                var cmsURL = editor_config.path_absolute + 'filemanager?field_name=' + field_name;

                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file: cmsURL,
                    judul: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no"
                });
            }
        };

        tinymce.init(editor_config);
    </script>
@endpush
