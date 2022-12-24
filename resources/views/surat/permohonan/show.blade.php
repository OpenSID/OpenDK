@extends('layouts.dashboard_template')

@section('title') Permohonan Surat @endsection

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('surat.permohonan') }}">Surat</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content container-fluid">

    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="float-right">
                <div class="btn-group">
                    <a href="{{ route('surat.permohonan') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Kembali ke Permohonan Surat
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="box-body">
            <object data='{{ asset("storage/surat/{$surat->file}") }}' style="width: 100%;min-height: 400px;" type="application/pdf"></object>
        </div>
        <div class="box-footer">
            <div class="form-group">
                <div class="text-center">
                    <button id="setujui" class="btn btn-primary">Setujui</button>
                    <button id="tolak" class="btn btn-danger">Tolak</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@include('partials.asset_sweetalert')

@push('scripts')
<script>
    $('#setujui').on('click', function () {
        Swal.fire({
        title: 'Apakah anda yakin ingin menyetujui surat?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya!',
        cancelButtonText: 'Tidak!',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(`{{ route('surat.permohonan.setujui', $surat->id) }}`)
            .then(response => {
                if (!response.ok) {
                throw new Error(response.statusText)
                }
                return response.json()
            })
            .catch(error => {
                Swal.showValidationMessage(
                `Request failed: ${error}`
                )
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Sukses!',
                    'Surat berhasil disetujui',
                    'success'
                )
                return window.location.replace(`{{ route('surat.permohonan') }}`);
            } else {
                Swal.fire(
                    'Gagal!',
                    'Surat gagal disetujui.',
                    'error'
                )
            }
        })
    });
</script>
@endpush
