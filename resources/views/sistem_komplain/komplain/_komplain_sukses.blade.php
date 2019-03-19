@php
use App\Models\Komplain;

$komplains = Komplain::where('status', '=', 'SELESAI')->orderBy('created_at', 'desc')->limit(5)->get();

@endphp

<!-- Success Box -->
<div class="box box-success">
    <div class="box-header">
        <i class="fa fa-check-square-o"></i>

        <h3 class="box-title">Komplain Selesai</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            @if(count($komplains) > 0 )
                @foreach($komplains as $item)
                    <li><a href="{{ route('sistem-komplain.komplain', $item->slug) }}"><i class="fa fa-comment"></i> {{ $item->judul }}</a></li>
                @endforeach
            @else
                <li><a href="#"><i class="fa fa-comment"></i> Data tidak ditemukan.</a></li>
            @endif
        </ul>
    </div>
    <!-- /.box-body -->
    @if(count($komplains) > 0 )
        <div class="box-footer">
            <a href="{{ route('sistem-komplain.komplain-sukses') }}" class="footer">Lihat Semua</a>
        </div>
    @endif
</div>
<!-- /.box -->