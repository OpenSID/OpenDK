@php $user = auth()->user(); @endphp

@if(isset($user) && (! $profil->kecamatan_id))
    <div class="callout callout-warning">
        <h4><i class="icon fa fa-warning"></i> Peringatan!</h4>
        <p>Data profil wilayah belum lengkap. Silahkan dilengkapi terlebih dahulu!. <a href="{{ route('data.profil.index') }}">Lengkapi</a></p>
    </div>
@endif

@if ($message = Session::get('success'))
    <div id="notifikasi" class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Sukses!</h4>
        <p>{{ $message }}</p>
    </div>
@elseif($message = Session::get('error'))
    <div id="notifikasi" class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Gagal!!</h4>
        <p>{{ $message }}</p>
    </div>
@elseif($message = Session::get('warning'))
    <div id="notifikasi" class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Perhatian!</h4>
        <p>{{ $message }}</p>
    </div>
@endif