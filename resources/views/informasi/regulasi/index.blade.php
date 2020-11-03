@extends('layouts.dashboard_template')

@section('content')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title or "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
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
                    <h3 class="box-title">Data Regulasi</h3>

                    <div class="box-tools pull-right">
                        <a href="{{route('informasi.regulasi.create')}}"
                           class="btn btn-primary btn-sm {{Sentinel::guest() ? 'hidden':''}}"><i class="fa fa-plus"></i> Tambah</a>
                    </div>
                </div>
                <!-- /.box-header -->
                @if(isset($regulasi))
                    <div class="box-body no-padding">

                        <table class="table table-striped">
                            <tr>
                                <th>Judul Regulasi</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                            @foreach($regulasi as $item)
                            <tr>

                                <td><a href="{{ route('informasi.regulasi.show', $item->id) }}">{{ $item->judul }}</a></td>

                                    @unless(!Sentinel::check())
                                    <td>
                                            <a href="{{ route('informasi.regulasi.edit', $item->id) }}">
                                                <button type="submit"
                                                        class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Ubah
                                                </button>
                                            </a>&nbsp;
                                            <a href="javascript:void(0)" class="" title="Hapus"
                                               data-href="{!! route('informasi.regulasi.destroy', $item->id) !!}"
                                               data-button="delete"
                                               id="deleteModal">
                                                <button type="button" class="btn btn-icon btn-danger btn-xs"><i class="fa fa-trash"
                                                                                                                aria-hidden="true"></i>
                                                    Hapus
                                                </button>
                                            </a>
                                    </td>
                                    @endunless

                            </tr>
                            @endforeach
                        </table>
                    </div>


                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        {!! $regulasi->links() !!}
                    </div>
                @else
                    <div class="box-body">
                        <h3>Data not found.</h3>
                        Sorry no data available right now!
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <div class="pull-right">

                        </div>
                    </div>
                    @endif
                            <!-- /.box-footer -->
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection



@include('partials.asset_select2')

@push('scripts')
<script>
    $(function () {

        $('#kecamatan').select2({
            placeholder: "Pilih Kecamatan",
            allowClear: true,
            ajax: {
                url: '{!! route('api.profil') !!}',
                dataType: 'json',
                delay: 200,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                        pagination: {
                            more: (params.page * 10) < data.total
                        }
                    };
                }
            },
            minimumInputLength: 1,
            templateResult: function (repo) {
                if (repo.loading) return repo.nama;
                var markup = repo.nama;
                return markup;
            },
            templateSelection: function (repo) {
                return repo.nama;
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            initSelection: function (element, callback) {

                //var id = $(element).val();
                var id = $('#defaultProfil').val();
                if (id !== "") {
                    $.ajax('{!! route('api.profil-byid') !!}', {
                        data: {id: id},
                        dataType: "json"
                    }).done(function (data) {
                        callback(data);
                    });
                }
            }
        });
    });
</script>

@include('forms.delete-modal')
@endpush


