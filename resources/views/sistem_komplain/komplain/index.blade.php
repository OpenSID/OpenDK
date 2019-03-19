@extends('layouts.dashboard_template')

@section('content')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title or "Page Title" }}
        <small>{{ $page_description or null }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    @include('partials.flash_message')

    <div class="row">
        <div class="col-md-3">
            @include('sistem_komplain.komplain._tracking')

            @include('sistem_komplain.komplain._komplain_populer')

            @include('sistem_komplain.komplain._komplain_sukses')
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <!-- quick email widget -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-comments"></i>

                    <h3 class="box-title">Daftar Komplain</h3>
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
                            @if(! Sentinel::guest())
                                <li class="pull-right">
                                    <a href="{{ route('sistem-komplain.komplain', $item->slug) }}" class="btn btn-xs btn-info"><i class="fa fa-search margin-r-5"></i>
                                        Lihat</a>
                                    <a href="{{ route('sistem-komplain.edit', $item->komplain_id) }}"
                                       class="btn btn-xs btn-primary"><i class="fa fa-edit margin-r-5"></i> Ubah</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['sistem-komplain.destroy', $item->id],'style'=>'display:inline']) !!}

                                    <button type="submit" class="btn btn-xs btn-danger"
                                            onclick="return confirm('Yakin akan menghapus data tersebut?')"><i
                                                class="fa fa-trash margin-r-5"></i> Hapus
                                    </button>

                                    {!! Form::close() !!}

                                </li>
                            @endif
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
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection