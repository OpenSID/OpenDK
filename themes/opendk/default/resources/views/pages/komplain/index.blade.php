@extends('layouts.app')

@section('content')
    <div class="col-md-8">
        @include('partials.flash_message')
        <div class="box box-primary">
            <div class="box-header">
                <i class="fa fa-comments"></i>
                <h3 class="box-title">Daftar Keluhan</h3>
            </div>
            <div class="box-body">
                @if (count($komplains) > 0)
                    @foreach ($komplains as $item)
                        <div class="post">
                            <div class="user-block">

                                {{-- di ambil dari detail_penduduk - api database gabungan --}}
                                @if(!empty($item->detail_penduduk))
                                <img class="img-circle img-bordered-sm" src="{{ is_user(json_decode($item->detail_penduduk)->foto, json_decode($item->detail_penduduk)->sex) }}" alt="user image">
                                @else

                                {{-- diambil dari relasi penduduk --}}
                                <img class="img-circle img-bordered-sm" src="{{ is_user($item?->penduduk?->foto, $item?->penduduk?->sex) }}" alt="user image">
                                @endif

                                <span class="username">
                                    <a href="{{ route('sistem-komplain.komplain', $item->slug) }}">{{ $item->judul }}</a>
                                    <a href="#" class="pull-right btn-box-tool"><span class="label label-default">{{ $item->kategori_komplain->nama }}</span></a>
                                </span>
                                <span class="description">{{ auth()->guest() && $item->anonim ? 'Anonim' : $item->nama }} melaporkan - {{ diff_for_humans($item->created_at) }}</span>
                            </div>
                            <p>
                                {!! get_words($item->laporan, 35) !!} ...
                            </p>
                            <ul class="list-inline">
                                <li><a href="{{ route('sistem-komplain.komplain', $item->slug) }}" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Tindak Lanjut Laporan ({{ count($item->jawabs) }})</a></li>
                            </ul>
                        </div>
                    @endforeach
                @else
                    <h3>
                        Data tidak tersedia!
                    </h3>
                @endif
            </div>
            <div class="box-footer clearfix">
                {!! $komplains->links() !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            setTimeout(function() {
                $("#notifikasi").slideUp("slow");
            }, 2000);
        })
    </script>
@endpush
