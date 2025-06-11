@extends('layouts.dashboard_template')

@section('title')
    Permohonan Surat
@endsection

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
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
                            <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Kembali
                                ke Permohonan Surat
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
                        @if ($surat->log_verifikasi == 4)
                            <button id="passphrase" class="btn btn-primary">Tandatangani</button>
                        @else
                            <button id="setujui" class="btn btn-primary">Setujui</button>
                            <button id="tolak" class="btn btn-danger">Tolak</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('partials.asset_sweetalert')

@push('scripts')
    <script>
        $('#setujui').on('click', function() {
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

        $('#tolak').on('click', function() {
            Swal.fire({
                title: 'Berikan alasan menolak surat ini.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Kirim!',
                cancelButtonText: 'Batal!',
                showLoaderOnConfirm: true,
                input: 'textarea',
                inputPlaceholder: 'Masukkan asalan',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Kolom keterangan tidak boleh kosong'
                    }
                },
                preConfirm: (value) => {
                    const formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('keterangan', value);
                    return fetch(`{{ route('surat.permohonan.tolak', $surat->id) }}`, {
                            method: 'POST',
                            body: formData,
                        })
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
                        'Surat berhasil ditolak',
                        'success'
                    )
                    return window.location.replace(`{{ route('surat.permohonan') }}`);
                } else {
                    Swal.fire(
                        'Gagal!',
                        'Surat gagal ditolak.',
                        'error'
                    )
                }
            })
        });

        $('#passphrase').on('click', function() {
            Swal.fire({
                title: 'Apakah anda yakin ingin menandatangani surat ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Kirim!',
                cancelButtonText: 'Batal!',
                showLoaderOnConfirm: true,
                input: 'password',
                inputAttributes: {
                    autocomplete: 'off'
                },
                inputPlaceholder: 'Masukkan passphrase',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Kolom passphrase tidak boleh kosong'
                    }
                },
                preConfirm: (value) => {
                    const formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('passphrase', value);
                    return fetch(`{{ route('surat.permohonan.passphrase', $surat->id) }}`, {
                            method: 'POST',
                            body: formData,
                        })
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
                    let response = result.value
                    if (response.status == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Request failed',
                            text: response.pesan_error,
                        })
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dokumen berhasil tertanda tangani secara elektronik',
                            showConfirmButton: true,
                        }).then((result) => {
                            return window.location.replace(`{{ route('surat.arsip') }}`);
                        })
                    }
                }
            })
        });
    </script>
@endpush
