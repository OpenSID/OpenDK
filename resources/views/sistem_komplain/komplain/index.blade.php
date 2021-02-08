@extends('layouts.app')

@section('content')
<!-- Main content -->
        <div class="col-md-8">
            <!-- quick email widget -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-comments"></i>
                    <h3 class="box-title">Daftar Keluhan</h3>
                </div>
                <div class="box-body">
                    @if(count($komplains) > 0)
                        @foreach ($komplains as $item)
                                <!-- Post -->
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm"
                                    src="{{ asset('/bower_components/admin-lte/dist/img/user2-160x160.jpg') }}"
                                    alt="user image">
                            <span class="username">
                                <a href="{{ route('sistem-komplain.komplain', $item->slug) }}">{{ $item->judul }}</a>
                                <a href="#" class="pull-right btn-box-tool"><span
                                            class="label label-default">{{ $item->kategori_komplain->nama }}</span></a>
                            </span>
                                <span class="description">{{ $item->nama }}
                                    melaporkan - {{ diff_for_humans($item->created_at) }}</span>
                            </div>
                            <!-- /.user-block -->
                            <p>
                                {!! get_words($item->laporan, 35) !!} ...
                            </p>
                            <ul class="list-inline">
                                <li><a href="{{ route('sistem-komplain.komplain', $item->slug) }}" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i>
                                        Tindak Lanjut Laporan ({{ count($item->jawabs) }})</a></li>
                            </ul>
                        </div>
                        <!-- /.post -->
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
        <!-- /.col -->
@endsection