@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        <div class="box box-info">
            <div class="box-header with-border text-center">
                {{-- tampilkan modal upload --}}
                <a href="javascript:void(0)" class="btn btn-social bg-blue btn-sm" data-toggle="modal" data-target="#modal-upload"><i class="fa fa-upload"></i> Unggah</a>
                <a href="{{ route('setting.themes.rescan') }}" class="btn btn-social bg-orange btn-sm"><i class="fa fa-recycle"></i> Pindai</a>
                {{-- <a href="{{ site_url() }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-eye"></i> Lihat</a> --}}
            </div>
        </div>
        <div class="row">
            @foreach ($themes as $key => $value)
                <div class="col-md-4">
                    @include('backend.themes.box', $value)
                </div>
            @endforeach
        </div>

        {{-- modal form unggah file .zip --}}
        <div class="modal fade" id="modal-upload" tabindex="-1" role="dialog" aria-labelledby="modal-upload-label">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modal-upload-label">Unggah Tema</h4>
                    </div>
                    <form id="upload-form" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="file" class="control-label">File<code>.zip</code></label>
                                <input type="file" name="file" id="file" class="form-control" accept=".zip" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left">Batal</button>
                            <button type="submit" class="btn btn-success">Unggah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('partials.asset_sweetalert')
@push('scripts')
    @include('forms.delete-modal')
    <script type="text/javascript">
        $(document).ready(function() {
            // unggah file .zip menggunakan ajax
            // notifikasi gunakan swal.fire
            $('#upload-form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('setting.themes.upload') }}",
                    data: formData,
                    enctype: 'multipart/form-data',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#modal-upload').modal('hide');
                        if (response.status == 'success') {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success'
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    }
                });
            });

        });
    </script>
    @include('forms.delete-modal')
@endpush
