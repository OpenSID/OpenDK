<?php
use App\Models\Komplain;

//$komplains = Komplain::where('status','<>', 'REVIEW')->where('status', '<>', 'DITOLAK')->orderBy('created_at', 'desc')->limit(5)->get();

$komplains =Komplain::leftJoin('das_jawab_komplain', 'das_komplain.komplain_id', '=', 'das_jawab_komplain.komplain_id')
        ->selectRaw('das_komplain.judul, das_komplain.slug, count(das_jawab_komplain.id) as total')
        ->where('status','<>', 'REVIEW')->where('status', '<>', 'DITOLAK')->where('status', '<>', 'SELESAI')
        ->groupBy('das_komplain.komplain_id', 'das_komplain.id', 'das_komplain.judul', 'das_komplain.slug')
        ->orderBy('total', 'DESC')
        ->limit(5)
        ->get();
?>

<!-- Trending Box -->
<div class="box box-primary">
    <div class="box-header">
        <i class="fa fa-line-chart"></i>
        <h3 class="box-title">Terpopuler</h3>
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
</div>
<!-- /.box -->