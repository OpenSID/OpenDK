<div class="form-group">
    <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Judul <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('judul', null, ['class' => 'form-control', 'required' => true, 'id' => 'judul']) !!}
    </div>
</div>
<div class="form-group">
    <label for="nama" class="control-label col-md-4 col-sm-3 col-xs-12">Deskripsi<span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('deskripsi', null, [
            'class' => 'textarea',
            'placeholder' => 'deskripsi',
            'style' => 'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;',
        ]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12">Gambar <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <input accept="jpg,jpeg,png" type="file" name="gambar" id="gambar" class="form-control" {{ $slide ? '' : 'required' }}>
        <code>Dimensi gambar 1360 x 400 Piksel</code>
        <br>

        <img src="@if (isset($slide->gambar)) {{ Str::contains($slide->gambar, 'storage') ? asset($slide->gambar) : $slide->gambar }} @else {{ 'http://placehold.co/1000x600' }} @endif" id="showgambar" style="max-width:400px;max-height:250px;float:left;" />

    </div>
</div>
<div class="ln_solid"></div>

@include('partials.asset_jqueryvalidation')

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\SlideRequest', '#form-slide') !!}

    <script>
        $(function() {

            var fileTypes = ['jpg', 'jpeg', 'png']; //acceptable file types

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var extension = input.files[0].name.split('.').pop().toLowerCase(), //file extension from input file
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
                        $("#gambar").val('');
                        alert('File tersebut tidak diperbolehkan.');
                    }
                }
            }

            $("#gambar").change(function() {
                readURL(this);
            });
        });
    </script>
@endpush
