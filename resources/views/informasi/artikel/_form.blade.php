<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.11/tinymce.min.js" referrerpolicy="origin"></script>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama artikel <span class="required text-danger">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('title', null, ['placeholder' => 'Nama artikel','class' => 'form-control']) !!}
        @if ($errors->has('title')) <span class="help-block" style="color:red">{{ $errors->first('title') }}</span> @endif
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Gambar <span class="required text-danger">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::file('image', ['placeholder' => 'Gambar','class' => 'form-control','id' => 'file-article']) !!}
        @if ($errors->has('image')) <span class="help-block" style="color:red">{{ $errors->first('image') }}</span> @endif
        <br>
        @if(isset($article->image))
            <img class="" src="{{ url($path) }}" id="showgambar" style="max-width:400px;max-height:250px;float:left;"/>
        @else
            <img class="hide" src=""  id="showgambar" style="max-width:400px;max-height:250px;float:left;"/>
        @endif
        <object data="" type="application/pdf" width="500" height="400" class="hide" id="showpdf"> </object>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi <span class="required text-danger">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('description', null,['class'=>'my-editor', 'placeholder'=>'Deskripsi kegiatan', 'style'=>'width: 100%;
         height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) !!}
        @if ($errors->has('description')) <span class="help-block" style="color:red">{{ $errors->first('description') }}</span> @endif
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required text-danger">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('is_active', ['0' => 'Tidak Aktif', '1' => 'Aktif'], null,['class' => 'form-control']) !!}
        @if ($errors->has('title')) <span class="help-block" style="color:red">{{ $errors->first('title') }}</span> @endif
    </div>
</div>

<!-- /.box-body -->
<div class="box-footer">
    <div class="pull-right">
        <div class="control-group">
            <a href="{{ route('informasi.artikel.index') }}">
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Batal</button>
            </a>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
        </div>
    </div>
</div>


@push('scripts')
<script>
    $(function () {

var fileTypes = ['jpg', 'jpeg', 'png', 'jpg', 'bmp', 'pdf'];  //acceptable file types

function readURL(input) {
    if (input.files && input.files[0]) {
        var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
                isSuccess = fileTypes.indexOf(extension) > -1;  //is extension in acceptable types

        if (isSuccess) { //yes
            var reader = new FileReader();
            reader.onload = function (e) {

                if(extension != 'pdf'){
                    $('#showgambar').attr('src', e.target.result);
                    $('#showgambar').removeClass('hide');
                    $('#showpdf').addClass('hide');
                }else{
                    $('#showpdf').attr('data', e.target.result);
                    $('#showpdf').removeClass('hide');
                    $('#showgambar').addClass('hide');
                }

            }

            reader.readAsDataURL(input.files[0]);
        }
        else { //no
            //warning
            $("#file-article").val('');
            alert('File tersebut tidak diperbolehkan.');
        }
    }
}

$("#file-article").change(function () {
    readURL(this);
});
});
</script>
<script>
    var editor_config = {
      path_absolute : "/",
      selector: "textarea.my-editor",
      plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern"
      ],
      toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
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
          title : 'Filemanager',
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