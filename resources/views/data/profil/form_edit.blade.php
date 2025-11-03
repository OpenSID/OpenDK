<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Provinsi <span class="required">*</span></label>
            <div class="col-md-2 col-sm-3 col-xs-12">

                @if ($adaDesa)
                <input name="provinsi_id" class="form-control" placeholder="00" type="text"
                    value="{{ $profil->provinsi_id }}" readonly />
                @elseif ($status_pantau)
                <input id="provinsi_id" class="form-control" placeholder="00" type="text" readonly
                    value="{{ $profil->provinsi_id }}" />
                <input id="nama_provinsi" type="hidden" name="nama_provinsi" value="{{ $profil->nama_provinsi }}" />
                @else
                <input id="provinsi_offline" name="provinsi_id" class="form-control" placeholder="00" type="text"
                    value="{{ $profil->provinsi_id }}" />
                @endif
            </div>
            <div class="col-md-5 col-sm-6 col-xs-12">
                @if ($adaDesa)
                <input name="nama_provinsi" class="form-control" type="text" value="{{ $profil->nama_provinsi }}"
                    readonly />
                @elseif ($status_pantau)
                <select class="form-control" id="list_provinsi" name="provinsi_id" style="width: 100%;">
                    <option selected value="" disabled>Pilih Provinsi</option>
                    @if ($profil->provinsi_id || $profil->nama_provinsi)
                    <option selected value="{{ $profil->provinsi_id }}">{{ $profil->nama_provinsi }}</option>
                    @endif
                </select>
                @else
                <input class="form-control" type="text" name="nama_provinsi" value="{{ $profil->nama_provinsi }}" />
                @endif
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kabupaten <span class="required">*</span></label>
            <div class="col-md-2 col-sm-3 col-xs-12">
                @if ($adaDesa)
                <input name="kabupaten_id" class="form-control" placeholder="00.00" type="text"
                    value="{{ $profil->kabupaten_id }}" readonly />
                @elseif ($status_pantau)
                <input id="kabupaten_id" class="form-control" placeholder="00.00" type="text" readonly
                    value="{{ $profil->kabupaten_id }}" />
                <input id="nama_kabupaten" type="hidden" name="nama_kabupaten" value="{{ $profil->nama_kabupaten }}" />
                @else
                <input id="kabupaten_offline" name="kabupaten_id" class="form-control" placeholder="00.00" type="text"
                    value="{{ $profil->kabupaten_id }}" />
                @endif
            </div>
            <div class="col-md-5 col-sm-6 col-xs-12">
                @if ($adaDesa)
                <input name="nama_kabupaten" class="form-control" type="text" value="{{ $profil->nama_kabupaten }}"
                    readonly />
                @elseif ($status_pantau)
                <select class="form-control" id="list_kabupaten" name="kabupaten_id" style="width: 100%;">
                    <option selected value="" disabled>Pilih Kabupaten</option>
                    @if ($profil->kabupaten_id || $profil->nama_kabupaten)
                    <option selected value="{{ $profil->kabupaten_id }}">{{ $profil->nama_kabupaten }}</option>
                    @endif
                </select>
                @else
                <input class="form-control" type="text" name="nama_kabupaten" value="{{ $profil->nama_kabupaten }}" />
                @endif
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kecamatan <span class="required">*</span></label>
            <div class="col-md-2 col-sm-3 col-xs-12">
                @if ($adaDesa)
                <input name="kecamatan_id" class="form-control" placeholder="00.00.00" type="text"
                    value="{{ $profil->kecamatan_id }}" readonly />
                @elseif ($status_pantau)
                <input id="kecamatan_id" class="form-control" placeholder="00.00.00" type="text" readonly
                    value="{{ $profil->kecamatan_id }}" />
                <input id="nama_kecamatan" type="hidden" name="nama_kecamatan" value="{{ $profil->nama_kecamatan }}" />
                @else
                <input id="kecamatan_offline" name="kecamatan_id" class="form-control" placeholder="00.00.00"
                    type="text" value="{{ $profil->kecamatan_id }}" />
                @endif
            </div>
            <div class="col-md-5 col-sm-6 col-xs-12">
                @if ($adaDesa)
                <input name="nama_kecamatan" class="form-control" type="text" value="{{ $profil->nama_kecamatan }}"
                    readonly />
                @elseif ($status_pantau)
                <select class="form-control" id="list_kecamatan" name="kecamatan_id" data-placeholder="Pilih kecamatan"
                    style="width: 100%;">
                    <option selected value="" disabled>Pilih Kecamatan</option>
                    @if ($profil->kecamatan_id || $profil->nama_kecamatan)
                    <option selected value="{{ $profil->kecamatan_id }}">{{ $profil->nama_kecamatan }}</option>
                    @endif
                </select>
                @else
                <input class="form-control" type="text" name="nama_kecamatan" value="{{ $profil->nama_kecamatan }}" />
                @endif
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Tahun Pembentukan <span
                    class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! html()->text('tahun_pembentukan', old('tahun_pembentukan', $profil->tahun_pembentukan ?? null))
                ->placeholder('Tahun Pembentukan')
                ->class('form-control')
                ->required() !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Dasar Hukum Pembentukan <span
                    class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! html()->text('dasar_pembentukan', old('dasar_pembentukan', $profil->dasar_pembentukan ?? null))
                ->placeholder('Dasar Hukum Pembentukan')
                ->class('form-control')
                ->required() !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Alamat <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! html()->textarea('alamat', old('alamat', $profil->alamat ?? null))
                ->placeholder('Alamat')
                ->class('form-control')
                ->rows(3)
                ->required() !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Kode Pos <span class="required">*</span></label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! html()->text('kode_pos', old('kode_pos', $profil->kode_pos ?? null))
                ->placeholder('13210')
                ->class('form-control')
                ->required() !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Telepon <span class="required">*</span></label>

            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! html()->text('telepon', old('telepon', $profil->telepon ?? null))
                ->placeholder('021-4567890')
                ->class('form-control')
                ->required() !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Email <span class="required">*</span></label>

            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! html()->text('email', old('email', $profil->email ?? null))
                ->placeholder('mail@mail.com')
                ->class('form-control')
                ->required() !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Struktur Organisasi</label>

            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! html()->file('file_struktur_organisasi')
                ->class('validate form-control')
                ->id('file_struktur')
                ->attribute('accept', '.jpg,.jpeg,.bmp,.png,.gif') !!}
                <br>
                <img src="{{ is_img($profil->file_struktur_organisasi) }}" id="showgambar"
                    style="max-width:200px;max-height:200px;float:left;" />
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Logo</label>

            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! html()->file('file_logo')
                ->class('validate form-control')
                ->id('file_logo')
                ->attribute('accept', '.jpg,.jpeg,.bmp,.png,.gif') !!}
                <br>
                <img src="{{ is_logo($profil->file_logo) }}" id="showgambar3"
                    style="max-width:200px;max-height:200px;float:left;" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Sambutan {{ $sebutan_kepala_wilayah }}</label>
            <div class="col-md-8 col-sm-6 col-xs-12">
                {!! html()->textarea('sambutan', old('sambutan', $profil->sambutan ?? null))
                ->class('textarea my-editor')
                ->placeholder('Sambutan ' . $sebutan_kepala_wilayah . ' ' . $profil->nama_kecamatan)
                ->style('width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd;
                padding: 10px;') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <legend>Visi & Misi</legend>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Visi</label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! html()->textarea('visi', old('visi', $profil->visi ?? null))
                ->class('textarea my-editor')
                ->placeholder('Visi Kecamatan')
                ->style('width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd;
                padding: 10px;') !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Misi</label>
            <div class="col-md-7 col-sm-6 col-xs-12">
                {!! html()->textarea('misi', old('misi', $profil->misi ?? null))
                ->class('textarea my-editor')
                ->placeholder('Misi Kecamatan')
                ->style('width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd;
                padding: 10px;') !!}
            </div>
        </div>
    </div>
</div>
<div class="ln_solid"></div>
@include('partials.asset_jqueryvalidation')