<div class="row">
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <a href="javascript:history.back()"><button type="button" class="btn btn-info btn-sm"><i class="fa fa-arrow-left"></i> Kembali</button></a>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label" for="judul">Judul Galeri <span class="required text-danger">*</span></label>

                    {!! Form::text('judul', null, ['placeholder' => 'Judul Galeri', 'class' => 'form-control']) !!}
                    @if ($errors->has('judul'))
                        <span class="help-block" style="color:red">{{ $errors->first('judul') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="control-label" for="jenis">Jenis <span class="required text-danger">*</span></label>
                    {!! Form::select('jenis', ['file' => 'File', 'url' => 'Link'], null, ['class' => 'form-control', 'id' => 'jenis']) !!}

                    @if ($errors->has('jenis'))
                        <span class="help-block" style="color:red">{{ $errors->first('jenis') }}</span>
                    @endif
                </div>
                <div class="form-group" id="view-url">
                    <label for="url" class="control-label">URL <span class="required text-danger">*</span></label>

                    {!! Form::text('link', null, ['class' => 'form-control']) !!}

                    @if ($errors->has('link'))
                        <span class="help-block" style="color:red">{{ $errors->first('link') }}</span>
                    @endif
                </div>
                <div class="form-group" id="image">
                    <label class="control-label" for="thumbnail">Thumbnail<span class="required text-danger">*</span></label>

                    <div id="preview-container" style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <!-- Preview Gambar Akan Ditempatkan Di Sini -->
                        @if (isset($galeri->gambar))
                            @foreach ($galeri->gambar as $image)
                                <img src="{{ isThumbnail("publikasi/galeri/{$image}") }}" style="width:100px; max-height:100px; margin: 5px;">
                            @endforeach
                        @endif
                    </div>

                    @if (isset($galeri->gambar) == false)
                        <img src="{{ asset('/img/no-image.png') }}" id="showthumbnail" style="width:100%; max-height:250px; float:left;" />
                    @endif

                    {!! Form::file('gambar[]', [
                        'placeholder' => 'Thumbnail',
                        'class' => 'form-control',
                        'id' => 'file-album',
                        'accept' => 'jpg,jpeg,png',
                        'multiple' => true,
                    ]) !!}

                    @if ($errors->has('gambar'))
                        <span class="help-block" style="color:red">{{ $errors->first('gambar') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label" for="status">Status<span class="required text-danger">*</span></label>
                    {!! Form::select('status', ['0' => 'Tidak Aktif', '1' => 'Aktif'], null, ['class' => 'form-control']) !!}
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

            function toggleUrlFields(type) {
                var viewUrl = $('#view-url');
                var image = $('#image');

                if (type == 'file') {
                    viewUrl.hide();
                    image.show();
                } else {
                    viewUrl.show();
                    image.hide();
                }
            }

            $('#jenis').on('change', function() {
                toggleUrlFields($(this).val());
            }).trigger('change');

            var fileTypes = ['jpg', 'jpeg', 'png']; //acceptable file types

            function readURL(input) {
                if (input.files && input.files.length > 0) {
                    // Kosongkan gambar default dan preview lama
                    $('#showthumbnail').hide(); // sembunyikan gambar default
                    $('#preview-container').empty(); // kosongkan container preview

                    // Loop melalui semua file yang dipilih
                    for (var i = 0; i < input.files.length; i++) {
                        var file = input.files[i];
                        var
                            extension = file.name.split('.').pop().toLowerCase(),
                            isSuccess = fileTypes.indexOf(extension) > -1; // cek tipe file

                        if (isSuccess) { // file yang valid
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                // Buat elemen gambar untuk preview
                                var img = $('<img />', {
                                    'src': e.target.result,
                                    'style': 'width:100px; max-height:100px; margin: 5px;'
                                });
                                $('#preview-container').append(img);
                            }

                            reader.readAsDataURL(file);
                        } else { // jika file tidak valid
                            $("#file-album").val(''); // reset input
                            alert('File tersebut tidak diperbolehkan.');
                        }
                    }
                } else {
                    // Jika tidak ada file, tampilkan kembali gambar default
                    $('#showthumbnail').show();
                    $('#preview-container').empty();
                }
            }

            // Trigger preview saat file diubah
            $("#file-album").change(function() {
                readURL(this);
            });
        });
    </script>
@endpush
