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
                    @if($counter_unread_keluar > 0)
                        <span class="label label-primary pull-right">{{$counter_unread_keluar}}</span>
                    @endif
                </a>
            </li>
            <li class="{{ (Request::is(['pesan/arsip'])? 'active' : '') }}">
                <a href="{{ route('pesan.arsip') }}"><i class="fa fa-archive"></i> Arsip
                </a>
            </li>
        </ul>
    </div>
    <!-- /.box-body -->
</div>
@if(!Request::is(['pesan/arsip']) && isset($sudah_dibaca))
<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Label </h3>

        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked" id="status">
            <input type="hidden" value="{{ $sudah_dibaca ?? '' }}" name="sudahdibaca">
            <li class="{{ ($sudah_dibaca == '0' ? 'active' : '') }}">
                <a href="javascript:;" data-sudahdibaca="0"><i class="fa fa-circle-o text-blue"></i> Belum Dibaca 
                </a>
            </li>
            <li class="{{ ($sudah_dibaca == 1 ? 'active' : '') }}">
                <a href="javascript:;" data-sudahdibaca="1"><i class="fa fa-circle-o text-red"></i> Sudah Dibaca</a>
            </li>
        </ul>
    </div>
    <!-- /.box-body -->
</div>
@endif

@push('scripts')
    <script type="text/javascript">
        $(function () {
            $('ul#status a').click(function (e) { 
                e.preventDefault();
                let page = $('#next-links').data('currentPage');
                let q = $('#cari-pesan').val();
                let desa_id = $('#list_desa').val();
                let sudahdibaca = $(this).data('sudahdibaca');
                window.location = window.location.origin + window.location.pathname +  '?' + $.param({page, desa_id, q, sudahdibaca})
            });
        });
    </script>
@endpush