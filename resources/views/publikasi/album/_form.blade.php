<div class="row">
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <a href="{{ route('publikasi.album.index') }}"><button type="button" class="btn btn-info btn-sm"><i class="fa fa-arrow-left"></i> Kembali</button></a>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label" for="judul">Judul Album</label>

                    {!! Form::text('judul', null, ['placeholder' => 'Judul Album', 'class' => 'form-control']) !!}
                    @if ($errors->has('judul'))
                        <span class="help-block" style="color:red">{{ $errors->first('judul') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label" for="thumbnail">Thumbnail</label>
                    <img src="{{ isThumbnail(isset($album->gambar) ? "publikasi/album/{$album->gambar}" : null) }}" id="showthumbnail" style="width:100%; max-height:250px; float:left;" />

                    {!! Form::file('gambar', ['placeholder' => 'Thumbnail', 'class' => 'form-control', 'id' => 'file-album', 'accept' => 'jpg,jpeg,png']) !!}
                    @if ($errors->has('thumbnail'))
                        <span class="help-block" style="color:red">{{ $errors->first('thumbnail') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="control-label" for="status">Status</label>
                    {!! Form::select('status', ['0' => 'Tidak Aktif', '1' => 'Aktif'], null, ['class' => 'form-control']) !!}
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

                            $('#showthumbnail').attr('src', e.target.result);
                            $('#showthumbnail').removeClass('hide');
                            $('#showpdf').addClass('hide');

                        }

                        reader.readAsDataURL(input.files[0]);
                    } else { //no
                        //warning
                        $("#file-album").val('');
                        alert('File tersebut tidak diperbolehkan.');
                    }
                }
            }

            $("#file-album").change(function() {
                readURL(this);
            });
        });
    </script>
@endpush
