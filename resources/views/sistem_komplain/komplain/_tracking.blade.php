<a href="{{ route('sistem-komplain.kirim') }}" class="btn btn-warning btn-block margin-bottom"><b><i class="fa fa-paper-plane"></i> Kirim Komplain</b></a>

<!-- Form Tracking Komplain -->
<div class="box box-danger box-solid">
    <div class="box-header with-border">
        <i class="fa fa-search"></i>

        <h3 class="box-title">Lacak Komplain Anda!</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body box-profile">
        <form class="form-horizontal" method="post" action="{{ route('sistem-komplain.tracking') }}">
            <div class="input-group input-group-sm">
                {{ csrf_field() }}
                <input class="form-control" required type="text" name="q" placeholder="Tracking ID Komplain Anda" />
                <span class="input-group-btn"><button type="submit" class="btn btn-warning btn-flat">Lacak</button></span>
            </div>
        </form>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->