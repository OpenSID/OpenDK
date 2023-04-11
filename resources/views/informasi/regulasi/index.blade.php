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
                    <a href="{{ route('informasi.regulasi.create') }}" class="btn btn-success btn-sm {{auth()->guest() ? 'hidden':''}}" title="Tambah"><i class="fa fa-plus"></i>&ensp; Tambah</a>
                </div>
                @if (count($regulasi) > 0)
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <th style="width: 150px">Aksi</th>
                                    <th>Judul Regulasi</th>
                                </tr>

                                @foreach($regulasi as $item)
                                <tr>
                                   
                                    <td class="text-center text-nowrap">
                                        @if(!auth()->guest()) 
                                        <?php

                                            // TODO : Pindahkan ke controller dan gunakan datatable
                                            $data['show_url']   = route('informasi.regulasi.show', $item->id);
                                            $data['edit_url']   = route('informasi.regulasi.edit', $item->id);
                                            $data['delete_url'] = route('informasi.regulasi.destroy', $item->id);
                                            $data['download_url'] = route('informasi.regulasi.download', $item->id);

                                            echo view('forms.aksi', $data);
                                        ?>
                                        @endif
                                    </td>
                                  

                                    <td>{{ $item->judul }}</td>

                                </tr>
                                @endforeach
                            </table>
                        </div>

                        <div class="box-footer clearfix">
                            {!! $regulasi->links() !!}
                        </div>
                    </div>
                @else
                    <div class="box-body">
                        <h3>Data tidak ditemukan.</h3>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('forms.delete-modal')
@endpush


