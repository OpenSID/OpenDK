<div class="form-group">
    {!! html()->label('nama_pemohon', 'Nama Pemohon')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->text('nama_pemohon')->class('form-control')->placeholder('Masukkan nama lengkap pemohon')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('nik', 'NIK')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->text('nik')->class('form-control')->placeholder('Masukkan NIK (Nomor Induk Kependudukan)')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('alamat', 'Alamat')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->textarea('alamat')->class('form-control')->rows(3)->placeholder('Masukkan alamat lengkap pemohon')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('nomor_telepon', 'Nomor Telepon')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->text('nomor_telepon')->class('form-control')->placeholder('Masukkan nomor telepon/WA')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('email', 'Email')->class('col-sm-3 control-label') !!}
    <div class="col-sm-9">
        {!! html()->email('email')->class('form-control')->placeholder('Masukkan alamat email (jika ada)') !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('rincian_informasi', 'Rincian Informasi')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->textarea('rincian_informasi')->class('form-control')->rows(4)->placeholder('Jelaskan rincian informasi yang diminta')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('tujuan_penggunaan', 'Tujuan Penggunaan')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->textarea('tujuan_penggunaan')->class('form-control')->rows(3)->placeholder('Jelaskan tujuan penggunaan informasi')->required() !!}
    </div>
</div>

<div class="form-group">
    {!! html()->label('cara_memperoleh', 'Cara Memperoleh')->class('col-sm-3 control-label')->required() !!}
    <div class="col-sm-9">
        {!! html()->select('cara_memperoleh', ['ONLINE' => 'Online (Dikirim via Email)', 'OFFLINE' => 'Offline (Diambil Langsung)'])->class('form-control')->required() !!}
    </div>
</div>

<input type="hidden" name="status" value="MENUNGGU">
