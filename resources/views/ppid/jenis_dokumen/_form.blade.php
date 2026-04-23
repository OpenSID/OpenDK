<div class="row">
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <a href="{{ route('ppid.jenis-dokumen.index') }}" class="btn btn-info btn-sm">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="box-body">
                <div class="form-group {{ $errors->has('nama') ? 'has-error' : '' }}">
                    <label class="control-label" for="nama">Nama Jenis Dokumen <span
                            class="text-danger">*</span></label>
                    {!! html()->text('nama', old('nama', $jenis->nama ?? null))->class('form-control')->placeholder('Masukkan nama dokumen...')->id('nama') !!}
                    @if ($errors->has('nama'))
                        <span class="help-block" style="color:red">{{ $errors->first('nama') }}</span>
                    @endif
                    @if ($errors->has('slug'))
                        <span class="help-block" style="color:red">{{ $errors->first('slug') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="control-label" for="deskripsi">Deskripsi</label>
                    {!! html()->textarea('deskripsi', old('deskripsi', $jenis->deskripsi ?? null))->class('form-control')->rows(3)->placeholder('Penjelasan singkat...')->id('deskripsi') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label">Warna Label</label>
                    {!! html()->input('color', 'kode', old('kode', $jenis->kode ?? '#000000'))->class('form-control')->style('height: 38px;') !!}
                </div>

                <div class="form-group">
                    <label class="control-label">Pilih Icon</label>
                    <div class="input-group" style="display: table; width: 100%;"> <input type="hidden" id="icon"
                            name="icon" value="{{ old('icon', $jenis->icon ?? 'fa fa-file') }}">

                        <span class="input-group-addon" style="width: 45px; background: #eee;">
                            <i id="icon-preview" class="fa {{ old('icon', $jenis->icon ?? 'fa fa-file') }}"></i>
                        </span>

                        <div class="input-group-btn" style="width: 100%;">
                            <button type="button" class="btn btn-default" data-toggle="collapse"
                                data-target="#icon-list" style="width: 100%; text-align: left;">
                                Pilih Icon <span class="caret pull-right" style="margin-top: 7px;"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="icon-list" class="collapse"
                    style="border: 1px solid #ddd; border-radius: 4px; padding: 10px; margin-bottom: 15px;">
                    <div class="row text-center" style="max-height: 150px; overflow-y: auto; margin: 0;">
                        @foreach ($icons as $icon)
                            <div class="col-xs-3 icon-item" style="padding: 5px; cursor: pointer;">
                                <i class="fa {{ $icon }} fa-2x select-this-icon"
                                    data-icon="fa {{ $icon }}"></i>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label" for="status">Status</label>
                    {!! html()->select('status', ['1' => 'Aktif', '0' => 'Tidak Aktif'], old('status', $jenis->status ?? '1'))->class('form-control')->id('status') !!}
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
        $(document).ready(function() {
            $(document).on('click', '.select-this-icon', function() {
                var iconClass = $(this).data('icon');
                $('#icon').val(iconClass);
                $('#icon-preview').attr('class', iconClass);
                $('#icon-list').collapse('hide');
            });
        });
    </script>
@endpush
