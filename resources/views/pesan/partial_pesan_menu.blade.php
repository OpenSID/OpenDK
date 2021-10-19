<a href="{{route('pesan.compose')}}" class="btn btn-primary btn-block margin-bottom">Buat Pesan</a>
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
                    @if($counter_unread > 0)
                        <span class="label label-primary pull-right">{{$counter_unread}}</span>
                    @endif
                </a>
            </li>
            <li class="{{ (Request::is(['pesan/keluar'])? 'active' : '') }}">
                <a href="{{ route('pesan.keluar') }}"><i class="fa fa-envelope-open"></i> Pesan Keluar
                    @if($counter_pesan_keluar > 0)
                        <span class="label label-primary pull-right">{{$counter_pesan_keluar}}</span>
                    @endif
                </a>
            </li>
            <li class="{{ (Request::is(['pesan/arsip'])? 'active' : '') }}">
                <a href="{{ route('pesan.arsip') }}"><i class="fa fa-archive"></i> Arsip
                    @if($counter_arsip > 0)
                        <span class="label label-primary pull-right">{{$counter_arsip}}</span>
                    @endif
                </a>
            </li>
        </ul>
    </div>
    <!-- /.box-body -->
</div>