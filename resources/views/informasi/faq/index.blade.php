@extends('layouts.dashboard_template')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content container-fluid">

    @include('partials.flash_message')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Daftar FAQ (Frequently Ask and Question)</h3>

                    <div class="box-tools pull-right">
                        <a href="{{ route('informasi.faq.create') }}" class="btn btn-primary btn-sm {{ auth()->guest() ? 'hidden':''}}"><i class="fa fa-plus"></i>&nbsp; Tambah</a>
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
                                                @unless(!auth()->check())
                                                    <a href="{!! route('informasi.faq.edit', $faq->id) !!}" class="btn btn-xs btn-primary" title="Ubah" data-button="edit"><i class="fa fa-edit"></i>&nbsp; Ubah</a>
                                                    <a href="javascript:void(0)" class="" title="Hapus" data-href="{!! route('informasi.faq.destroy', $faq->id) !!}" data-button="delete" id="deleteModal">
                                                        <button type="button" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp; Hapus</button>
                                                    </a>
                                                @endunless
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            @else
                                <h3>Data tidak ditemukan.</h3>
                            @endif

                        </section>
                    </section>
                </div>
                <div class="box-footer clearfix">
                    {!! $faqs->links() !!}
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')

@include('forms.delete-modal')

@endpush