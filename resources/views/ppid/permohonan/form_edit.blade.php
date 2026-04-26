<div class="form-group">
    {!! html()->label('nama_pemohon', 'Nama Pemohon')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->text('nama_pemohon', $permohonan->nama_pemohon)->class('form-control')->placeholder('Masukkan nama lengkap pemohon')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('nik', 'NIK')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->text('nik', $permohonan->nik)->class('form-control')->placeholder('Masukkan NIK (Nomor Induk Kependudukan)')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('alamat', 'Alamat')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->textarea('alamat', $permohonan->alamat)->class('form-control')->rows(3)->placeholder('Masukkan alamat lengkap pemohon')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('nomor_telepon', 'Nomor Telepon')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->text('nomor_telepon', $permohonan->nomor_telepon)->class('form-control')->placeholder('Masukkan nomor telepon/WA')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('email', 'Email')->class('col-sm-3 control-label') !!}
    <div class="col-sm-9">
        {!! html()->email('email', $permohonan->email)->class('form-control')->placeholder('Masukkan alamat email (jika ada)') !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('rincian_informasi', 'Rincian Informasi')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->textarea('rincian_informasi', $permohonan->rincian_informasi)->class('form-control')->rows(4)->placeholder('Jelaskan rincian informasi yang diminta')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('tujuan_penggunaan', 'Tujuan Penggunaan')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->textarea('tujuan_penggunaan', $permohonan->tujuan_penggunaan)->class('form-control')->rows(3)->placeholder('Jelaskan tujuan penggunaan informasi')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('cara_memperoleh', 'Cara Memperoleh')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->select('cara_memperoleh', ['ONLINE' => 'Online (Dikirim via Email)', 'OFFLINE' => 'Offline (Diambil Langsung)'], $permohonan->cara_memperoleh)->class('form-control')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('status', 'Status')->class('col-sm-3 control-label') !!}
    <div class="col-sm-9">
        <div class="radio">
            <label>
                <input type="radio" name="status" value="MENUNGGU" {{ $permohonan->status == 'MENUNGGU' ? 'checked' : '' }}> Menunggu
            </label>
            &nbsp;
            <label>
                <input type="radio" name="status" value="DIPROSES" {{ $permohonan->status == 'DIPROSES' ? 'checked' : '' }}> Diproses
            </label>
            &nbsp;
            <label>
                <input type="radio" name="status" value="SELESAI" {{ $permohonan->status == 'SELESAI' ? 'checked' : '' }}> Selesai
            </label>
            &nbsp;
            <label>
                <input type="radio" name="status" value="DITOLAK" {{ $permohonan->status == 'DITOLAK' ? 'checked' : '' }}> Ditolak
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    {!! html()->label('keterangan', 'Keterangan')->class('col-sm-3 control-label') !!}
    <div class="col-sm-9">
        {!! html()->textarea('keterangan', $permohonan->keterangan)->class('form-control')->rows(3)->placeholder('Tambahkan keterangan jika diperlukan') !!}
    </div>
</div>
