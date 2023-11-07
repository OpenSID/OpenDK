<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-light">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="control-sidebar-home-tab">
            <h3 class="text-center"><strong>Catatan Rilis</strong></h3>
            @php
                $files = base_path('catatan_rilis.md');
                $content = parsedown($files);
            @endphp
            {!! $content !!}

            <hr style="border: 1px solid #ddd;">
            <p>Jika Anda butuh bantuan untuk menggunakan Aplikasi Dashboard Kecamatan, silahkan Anda Unduh Panduan Pengguna di bawah ini.</p>
            <br>
            <a href="{{ asset('storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf') }}" target="_blank" class="btn btn-primary btn-lg col-md-12"><i class="fa fa-download"></i> Unduh Panduan</a>
            <br>
            <br>
        </div>
        <!-- /.tab-pane -->
    </div>
</aside>
<!-- /.control-sidebar -->
