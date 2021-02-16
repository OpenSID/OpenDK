<div class="row">
    <div class="col-md-6">
        <legend>DATA DIRI</legend>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-3 col-xs-12">Foto</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <img src="https://placehold.it/120x150" id="showgambar"
                    style="max-width:120px;max-height:150px;float:left;"/>
            </div>
        </div>

        <div class="form-group">
            <label for="foto" class="control-label col-md-4 col-sm-3 col-xs-12">Ganti Foto</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" id="foto" name="foto" class="validate form-control"/>
            </div>
        </div>

        <div class="form-group">
            <label for="nama_pegawai" class="control-label col-md-4 col-sm-3 col-xs-12">Nama <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nama_pegawai', null,['placeholder'=>'Nama Pegawai', 'class'=>'form-control', 'required', 'id'=>'nama_pegawai']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="nik" class="control-label col-md-4 col-sm-3 col-xs-12">NIK<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nik', null,['placeholder'=>'Nomor Induk Kependudukan', 'class'=>'form-control', 'required', 'id'=>'nik']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="jenis_kelamin" class="control-label col-md-4 col-sm-3 col-xs-12">Jenis Kelamin<span class="required">*</span></label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if(old('jenis_kelamin') == 1) active @endif">
                        <input type="radio" name="jenis_kelamin" id="jenis_kelamin" value="1" autocomplete="off" @if(old('jenis_kelamin') == 1) checked @endif> Laki-Laki
                    </label>
                    <label class="btn btn-primary btn-sm @if(old('jenis_kelamin') == 2) active @endif">
                        <input type="radio" name="jenis_kelamin" id="jenis_kelamin" value="2" autocomplete="off" @if(old('jenis_kelamin') == 2) checked @endif> Perempuan
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="agama_id" class="control-label col-md-4 col-sm-3 col-xs-12">Agama<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('agama_id', \App\Models\Agama::pluck('nama', 'id'), null,['placeholder'=>'-Pilih', 'class'=>'form-control', 'id'=>'agama_id', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="tanggal_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tanggal Lahir<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tanggal_lahir', null,['placeholder'=>'0000-00-00', 'class'=>'form-control datepicker', 'required', 'id'=>'tanggal_lahir']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="tempat_lahir" class="control-label col-md-4 col-sm-3 col-xs-12">Tempat Lahir<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tempat_lahir', null,['placeholder'=>'Tempat Lahir', 'class'=>'form-control', 'required', 'id'=>'tempat_lahir']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="status_kawin_id" class="control-label col-md-4 col-sm-3 col-xs-12">Status Kawin<span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('status_kawin_id', \App\Models\Kawin::pluck('nama', 'id'), null,['placeholder'=>'-Pilih Status Kawin-', 'class'=>'form-control', 'id'=>'status_kawin_id', 'required']) !!}
            </div>
        </div>
        
        <div class="form-group">
            <label for="telepon" class="control-label col-md-4 col-sm-3 col-xs-12">Nomor Telepon<span class="required">*</span></label>
    
            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('telepon', null,['placeholder'=>'021-5432109', 'class'=>'form-control', 'id'=>'telepon']) !!}
            </div>
        </div>
    
        <div class="form-group">
            <label for="alamat" class="control-label col-md-4 col-sm-3 col-xs-12">Alamat<span class="required">*</span></label>
    
            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('alamat', null,['placeholder'=>'Alamat ', 'class'=>'form-control', 'required', 'id'=>'alamat']) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <legend>DATA PENDIDIKAN</legend>
    
        <div class="form-group">
            <label for="pendidikan" class="control-label col-md-4 col-sm-3 col-xs-12">Pendidikan Terakhir<span class="required">*</span></label>
    
            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('pendidikan', null,['placeholder'=>'Pendidikan : Jenjang / Jurusan', 'class'=>'form-control', 'id'=>'telepon']) !!}
            </div>
        </div>
    
        <div class="form-group">
            <label for="tamat_pendidikan" class="control-label col-md-4 col-sm-3 col-xs-12">Tamat Pendidikan<span class="required">*</span></label>
    
            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tamat_pendidikan', null,['placeholder'=>'0000-00-00', 'class'=>'form-control datepicker', 'required', 'id'=>'tamat_pendidikan']) !!}
            </div>
        </div>
        <legend>DATA KEPEGAWAIAN</legend>
        <div class="form-group">
            <label for="nip" class="control-label col-md-4 col-sm-3 col-xs-12">NIP</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('nip', null,['placeholder'=>'NIP', 'class'=>'form-control',  'id'=>'nip']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="no_karpeg" class="control-label col-md-4 col-sm-3 col-xs-12">No. Karpeg</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('no_karpeg', null,['placeholder'=>'Nomor Kartu Pegawai', 'class'=>'form-control',  'id'=>'no_karpeg']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="alamat" class="control-label col-md-4 col-sm-3 col-xs-12">Pangkat CPNS</label>
            
            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('pangkat_cpns',['Juru Muda','Juru Muda Tingkat I','Juru','Juru Tingkat I','Pengatur Muda','Pengatur Muda Tingkat I','Pengatur','Pengatur Tingkat I','Penata Muda'], null,['placeholder'=>'Pangkat CPNS ', 'class'=>'form-control', 'id'=>'pangkat_cpns']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="tmt_cpns" class="control-label col-md-4 col-sm-3 col-xs-12">TMT CPNS</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tmt_cpns', null,['placeholder'=>'0000-00-00', 'class'=>'form-control datepicker', 'required', 'id'=>'tmt_cpns']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="alamat" class="control-label col-md-4 col-sm-3 col-xs-12">Pangkat</label>
            
            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('pangkat',['Juru Muda','Juru Muda Tingkat I','Juru','Juru Tingkat I','Pengatur Muda','Pengatur Muda Tingkat I','Pengatur','Pengatur Tingkat I','Penata Muda','Penata Muda Tingkat I','Penata','Penata Tingkat I','Pembina','Pembina Tingkat I','Pembina Utama Muda','Pembina Utama Madya','Pembina Utama'],null,['placeholder'=>'Pangkat ', 'class'=>'form-control', 'id'=>'pangkat']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="tmt_cpns" class="control-label col-md-4 col-sm-3 col-xs-12">TMT PANGKAT</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tmt_cpns', null,['placeholder'=>'0000-00-00', 'class'=>'form-control datepicker', 'required', 'id'=>'tmt_cpns']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="golongan" class="control-label col-md-4 col-sm-3 col-xs-12">Golongan Ruang</label>

            <div class="col-md-3 col-sm-3 col-xs-6">
                {!! Form::select('golongan', ['I','II','III','IV'],null,['placeholder'=>'golongan ', 'class'=>'form-control', 'id'=>'golongan']) !!}
            </div>
            <div class="col-md-3 col-sm-3 col-xs-6">
                {!! Form::select('ruang',['a','b','c','d','e'], null,['placeholder'=>'Ruang ', 'class'=>'form-control', 'id'=>'ruang']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="jabatab_id" class="control-label col-md-4 col-sm-3 col-xs-12">Jabatan</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('jabatan_id', $jabatan, null,['placeholder'=>'Jabatan ', 'class'=>'form-control', 'id'=>'jabatan_id']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="tmt_cpns" class="control-label col-md-4 col-sm-3 col-xs-12">TMT JABATAN</label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::text('tmt_cpns', null,['placeholder'=>'0000-00-00', 'class'=>'form-control datepicker', 'required', 'id'=>'tmt_cpns']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="status" class="control-label col-md-4 col-sm-3 col-xs-12">Status Pengabdian</label>
            <div class="input-group col-md-6 col-sm-6 col-xs-12">
                &nbsp;
                &nbsp;
                &nbsp;
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-primary btn-sm @if(old('status') == 1) active @endif">
                        <input type="radio" name="status" id="status" value="1" autocomplete="off" @if(old('status') == 1) checked @endif> Tidak Aktif
                    </label>
                    <label class="btn btn-primary btn-sm @if(old('status') == 2) active @endif">
                        <input type="radio" name="status" id="status" value="2" autocomplete="off" @if(old('status') == 2) checked @endif> Aktif
                    </label>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="ln_solid"></div>