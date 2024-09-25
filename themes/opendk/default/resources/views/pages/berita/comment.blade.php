<form action="{{ route('comments.modal.store') }}" method="POST">
    @csrf
    <input type="hidden" name="comment_id" value="{{ $commentId }}">
    <input type="hidden" name="das_artikel_id" value="{{ $artikelId }}">

    <div class="form-group">
        <input type="text" class="form-control" name="nama" placeholder="Nama" required>
    </div>

    <div class="form-group">
        <input type="email" class="form-control" name="email" placeholder="Email" required>
    </div>

    <div class="form-group">
        <textarea class="form-control" name="body" rows="3" placeholder="Tulis balasan disini.." required></textarea>
    </div>

    <!-- CAPTCHA for Reply -->
    <div class="form-group">
        <div class="captcha" style="margin-bottom: 5px;">
            <span>{!! captcha_img('mini') !!}</span>
            <button type="button" class="btn btn-success btn-refresh"><i class="fa fa-refresh"></i></button>
        </div>
        <input type="text" class="form-control" placeholder="Masukan Kode Verifikasi" name="captcha_main" required>
    </div>

    <button type="submit" class="btn btn-primary btn-block">Kirim Balasan</button>
</form>
