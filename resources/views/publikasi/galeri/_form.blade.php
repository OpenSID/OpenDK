<div class="row">
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <a href="{{ route('publikasi.galeri.index', Session::get('album_id')) }}" class="btn btn-info btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label" for="judul">Judul Galeri <span class="required text-danger">*</span></label>

                    {!! html()->text('judul')->class('form-control')->placeholder('Judul Galeri')->value(old('judul', isset($galeri) ? $galeri->judul : '')) !!}
                    @if ($errors->has('judul'))
                        <span class="help-block" style="color:red">{{ $errors->first('judul') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="control-label" for="jenis">Jenis <span class="required text-danger">*</span></label>
                    {!! html()->select('jenis', ['file' => 'File', 'url' => 'Link'])->class('form-control')->value(old('jenis', isset($galeri) ? $galeri->jenis : '')) !!}

                    @if ($errors->has('jenis'))
                        <span class="help-block" style="color:red">{{ $errors->first('jenis') }}</span>
                    @endif
                </div>
                <div class="form-group" id="view-url">
                    <label for="url" class="control-label">URL <span class="required text-danger">*</span></label>

                    {!! html()->text('link')->class('form-control')->value(old('link', isset($galeri) ? $galeri->link : '')) !!}

                    @if ($errors->has('link'))
                        <span class="help-block" style="color:red">{{ $errors->first('link') }}</span>
                    @endif
                </div>
                <div class="form-group" id="image">
                    <label class="control-label" for="file-galeri">Thumbnail @if(!isset($galeri) || empty($galeri->gambar))<span class="required text-danger">*</span>@endif</label>

                    <input type="file" name="gambar[]" id="file-galeri" class="form-control" accept=".jpg,.jpeg,.png" multiple>
                    <x-upload-hint formats="JPG, JPEG, PNG" :limit-kb="1024" />
                    <br />

                    @if (isset($galeri) && !empty($galeri->gambar))
                        @foreach ((array) $galeri->gambar as $image)
                            @php $imageUrl = asset('storage/publikasi/galeri/' . $image); @endphp
                            <div style="padding:8px; background:#f5f5f5; border-radius:4px; margin-bottom:8px; display:flex; align-items:center; gap:10px;">
                                <a href="javascript:void(0)" data-toggle="modal-image" data-url="{{ $imageUrl }}" data-title="{{ $image }}" title="Klik untuk memperbesar">
                                    <img src="{{ $imageUrl }}" style="max-height:60px; max-width:90px; object-fit:contain; border-radius:3px; cursor:pointer;" class="img-thumbnail">
                                </a>
                                <div style="overflow: hidden; flex-grow: 1;">
                                    <span class="text-muted" style="word-break: break-all;">{{ $image }}</span><br>
                                    <a href="javascript:void(0)" data-toggle="modal-image" data-url="{{ $imageUrl }}" data-title="{{ $image }}" class="btn btn-xs btn-info" style="margin-top: 4px;">
                                        <i class="fa fa-eye"></i> Pratinjau
                                    </a>
                                    <a href="{{ $imageUrl }}" download="{{ $image }}" class="btn btn-xs btn-default" style="margin-top: 4px;">
                                        <i class="fa fa-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        <small class="help-block text-muted">
                            Upload file baru di atas untuk menggantikan file yang ada.
                        </small>
                    @endif

                    <div class="clearfix"></div>
                    <br>
                    <div id="preview-container">
                        <img class="hide" id="showgambar" style="max-width:400px;max-height:250px;float:left;" />
                    </div>
                    <div class="clearfix"></div>

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
                    <label class="control-label" for="status">Status <span class="required text-danger">*</span></label>
                    {!! html()->select('status', ['0' => 'Tidak Aktif', '1' => 'Aktif'])->class('form-control')->value(old('status', isset($galeri) ? $galeri->status : '')) !!}
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
            var objectUrls = [];

            function cleanupObjectUrls() {
                objectUrls.forEach(function(url) {
                    URL.revokeObjectURL(url);
                });
                objectUrls = [];
            }

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

            var fileTypes = ['jpg', 'jpeg', 'png'];

            function previewImages(input) {
                cleanupObjectUrls();
                var $container = $('#preview-container');
                $container.empty();

                if (input.files && input.files.length > 0) {
                    var invalidFiles = false;

                    for (var i = 0; i < input.files.length; i++) {
                        var file = input.files[i];
                        var extension = file.name.split('.').pop().toLowerCase();

                        if (fileTypes.indexOf(extension) === -1) {
                            invalidFiles = true;
                            break;
                        }
                    }

                    if (invalidFiles) {
                        $(input).val('');
                        if (typeof openAlert === 'function') {
                            openAlert('File tersebut tidak diperbolehkan. Hanya format JPG, JPEG, dan PNG yang didukung.', 'Peringatan', 'warning');
                        } else {
                            alert('File tersebut tidak diperbolehkan. Hanya format JPG, JPEG, dan PNG yang didukung.');
                        }
                        return;
                    }

                    for (var j = 0; j < input.files.length; j++) {
                        var validFile = input.files[j];
                        var objectUrl = URL.createObjectURL(validFile);
                        objectUrls.push(objectUrl);

                        var $img = $('<img />', {
                            src: objectUrl,
                            id: j === 0 ? 'showgambar' : undefined,
                            'data-toggle': 'modal-image',
                            'data-url': objectUrl,
                            'data-title': validFile.name,
                            title: 'Klik untuk memperbesar',
                            style: 'max-width:400px;max-height:250px;float:left;margin-right:10px;margin-bottom:10px;cursor:pointer;'
                        });

                        $container.append($img);
                    }
                } else {
                    $container.html('<img class="hide" id="showgambar" style="max-width:400px;max-height:250px;float:left;" />');
                }
            }

            $('#file-galeri').on('change', function() {
                previewImages(this);
            });
        });
    </script>
@endpush

<x-modal-image />
