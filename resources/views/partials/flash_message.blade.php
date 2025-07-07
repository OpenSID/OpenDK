@php $user = auth()->user(); @endphp

@if ($message = Session::get('success'))
    <div id="notifikasi" class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Sukses!</h4>
        <p>{{ $message }}</p>
    </div>
@elseif($message = Session::get('error') ?? Session::get('error_api'))
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
@elseif($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
