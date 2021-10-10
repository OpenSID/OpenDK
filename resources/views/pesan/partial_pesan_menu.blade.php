<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Kategori</h3>

        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li class="{{ (Request::is(['pesan'])? 'active' : '') }}">
                <a href="{{ route('pesan.index') }}"><i class="fa fa-envelope"></i> Pesan Masuk
                    <span class="label label-primary pull-right">12</span>
                </a>
            </li>
            <li class="{{ (Request::is(['pesan/keluar'])? 'active' : '') }}">
                <a href="{{ route('pesan.keluar') }}"><i class="fa fa-envelope-open"></i> Pesan Keluar</a>
            </li>
            <li class="{{ (Request::is(['pesan/arsip'])? 'active' : '') }}">
                <a href="{{ route('pesan.arsip') }}"><i class="fa fa-archive"></i> Arsip</a>
            </li>
        </ul>
    </div>
    <!-- /.box-body -->
</div>