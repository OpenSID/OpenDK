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
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Daftar FAQ</h3>

                    <div class="box-tools pull-right">
                        <a href="{{route('informasi.faq.create')}}"
                           class="btn btn-primary btn-sm {{Sentinel::guest() ? 'hidden':''}}"><i class="fa fa-plus"></i> Tambah</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <section class="content-max-width">
                        <section id="faq">
                            @if(count($faqs) > 0)
                                @foreach($faqs as $faq)
                                    <h3>{{$faq->question}}</h3>

                                    <p>{!! $faq->answer !!}</p>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                @unless(!Sentinel::check())
                                                    <a href="{{ route('informasi.faq.edit', $faq->id) }}">
                                                        <button type="submit"
                                                                class="btn btn-sm btn-primary">Ubah
                                                        </button>
                                                    </a>&nbsp;
                                                    {!! Form::open(['method' => 'DELETE','route' => ['informasi.faq.destroy', $faq->id],'style'=>'display:inline']) !!}

                                                    {!! Form::submit('Hapus', ['class' => 'btn btn-sm btn-danger', 'onclick' => 'return confirm("Yakin akan menghapus data tersebut?")']) !!}

                                                    {!! Form::close() !!}
                                                @endunless
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            @else
                                <h3>Maaf, FAQ belum tersedia.</h3>
                            @endif

                        </section>
                    </section>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    {!! $faqs->links() !!}
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection