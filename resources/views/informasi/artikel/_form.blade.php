<div class="row">
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <a href="{{ route('informasi.artikel.index') }}"><button type="button" class="btn btn-info btn-sm"><i class="fa fa-arrow-circle-left"></i> Kembali</button></a>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label" for="judul">Judul Artikel</label>
                    
                    {!! Form::text('judul', null, ['placeholder' => 'Judul Artikel', 'class' => 'form-control']) !!}
                    @if ($errors->has('judul')) <span class="help-block" style="color:red">{{ $errors->first('judul') }}</span> @endif
                    <!-- <span class="help-block"><code>Judul minimal 5 karakter dan maksimal 100 karakter</code></span> -->
                </div>

                <div class="form-group">
                    <label class="control-label" for="isi">Isi Artikel</label>

                    {!! Form::textarea('isi', null, ['class' => 'my-editor', 'placeholder' => 'Isi Artikel', 'style' => 'width: 100%; height: 750px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
                    @if ($errors->has('isi')) <span class="help-block" style="color:red">{{ $errors->first('isi') }}</span> @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label" for="gambar">Gambar</label>

                    <img src="{{ is_img($artikel->gambar ?? null) }}" id="showgambar" style="width:100%; max-height:250px; float:left;"/>

                    {!! Form::file('gambar', ['placeholder' => 'Gambar', 'class' => 'form-control', 'id' => 'file-artikel']) !!}
                    @if ($errors->has('gambar')) <span class="help-block" style="color:red">{{ $errors->first('gambar') }}</span> @endif
                </div>

                <div class="form-group">
                    <label class="control-label" for="gambar">Status</label>

                    {!! Form::select('status', ['0' => 'Tidak Aktif', '1' => 'Aktif'], null, ['class' => 'form-control']) !!}
                    @if ($errors->has('status')) <span class="help-block" style="color:red">{{ $errors->first('status') }}</span> @endif
                </div>
            </div>

            <div class="box-footer">
                <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-save"></i> Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.11/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    $(function () {

        $('.textarea').wysihtml5();
        
        var fileTypes = ['jpg', 'jpeg', 'png', 'jpg'];  //acceptable file types

        function readURL(input) {
            if (input.files && input.files[0]) {
                var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
                        isSuccess = fileTypes.indexOf(extension) > -1;  //is extension in acceptable types

                if (isSuccess) { //yes
                    var reader = new FileReader();
                    reader.onload = function (e) {

                        $('#showgambar').attr('src', e.target.result);
                        $('#showgambar').removeClass('hide');
                        $('#showpdf').addClass('hide');

                    }

                    reader.readAsDataURL(input.files[0]);
                }  else { //no
                    //warning
                    $("#file-artikel").val('');
                    alert('File tersebut tidak diperbolehkan.');
                }
            }
        }

        $("#file-artikel").change(function () {
            readURL(this);
        });
    });

    var editor_config = {
        path_absolute : "/",
        selector: "textarea.my-editor",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link gambar media",
        relative_urls: false,
        file_browser_callback : function(field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;
            var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
            
            if (type == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }
    
            tinyMCE.activeEditor.windowManager.open({
                file : cmsURL,
                judul : 'Filemanager',
                width : x * 0.8,
                height : y * 0.8,
                resizable : "yes",
                close_previous : "no"
            });
        }
    };
    
    tinymce.init(editor_config);
</script>

@endpush