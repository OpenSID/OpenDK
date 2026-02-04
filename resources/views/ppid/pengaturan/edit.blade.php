@extends('layouts.dashboard_template')

@section('content')
<section class="content-header block-breadcrumb">
    <h1>
        {{ $page_title ?? 'Page Title' }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_description ?? '' }}</li>
    </ol>
</section>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Ada kesalahan pada kolom inputan.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- form start -->
                {!! html()->form('PUT', route('ppid.pengaturan.update', $pengaturan->id))
                ->id('form-ppid-pengaturan')->class('fform-label-left')->acceptsFiles()->open() !!}

                <div class="box-body">

                    @include('flash::message')
                    @include('ppid.pengaturan._form')

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    @include('partials.button_reset_submit')
                </div>
                {!! html()->form()->close() !!}
            </div>
        </div>
    </div>
</section>
@endsection

<!-- Modal Tambah Pertanyaan -->
<div class="modal fade" id="modalTambahPertanyaan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-plus-circle"></i> Tambah Pertanyaan
                </h4>
            </div>
            <form id="formTambahPertanyaan">
                <div class="modal-body">
                    <input type="hidden" name="ppid_tipe" id="inputPpidTipe" value="1">

                    <div class="form-group">
                        <label>Judul Pertanyaan <span class="required">*</span></label>
                        <input type="text" name="ppid_judul" id="inputPpidJudul"
                            class="form-control" placeholder="Masukkan judul pertanyaan" required>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="required">*</span></label>
                        <select name="ppid_status" id="inputPpidStatus" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // CSRF Token untuk AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        var fileTypes = ['jpg', 'jpeg', 'png', 'bmp']; //acceptable file types

        function readURL(input) {
            if (input.files && input.files[0]) {
                var extension = input.files[0].name.split('.').pop()
                    .toLowerCase(),
                    isSuccess = fileTypes.indexOf(extension) > -1;

                if (isSuccess) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#banner-preview').attr('src', e.target.result);
                        $('#banner-preview').css('opacity', '1').css('box-shadow', '0 2px 4px rgba(0,0,0,0.1)');
                    }

                    reader.readAsDataURL(input.files[0]);
                } else {
                    $("#ppid_banner").val('');
                    alert('File tersebut tidak diperbolehkan. Gunakan file jpg, jpeg, png, atau bmp.');
                }
            }
        }

        $("#ppid_banner").change(function() {
            readURL(this);
        });

        // Handle submit form tambah pertanyaan
        $('#formTambahPertanyaan').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            var url = '{{ route("ppid.pertanyaan.store") }}';

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#modalTambahPertanyaan').modal('hide');
                        location.reload();
                    } else {
                        alert('Gagal menambahkan pertanyaan.');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        for (var key in errors) {
                            errorMessage += errors[key][0] + '\n';
                        }
                        alert(errorMessage);
                    } else {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                }
            });
        });
    });

    // Tampilkan modal tambah pertanyaan berdasarkan tipe
    function showTambahPertanyaanModal(tipe) {
        $('#inputPpidTipe').val(tipe);
        $('#inputPpidJudul').val('');
        $('#inputPpidStatus').val('1');

        var tipeLabels = {
            '0': 'Keberatan',
            '1': 'Informasi',
            '2': 'Mendapatkan'
        };

        $('#modalTambahPertanyaan .modal-title').html(
            '<i class="fa fa-plus-circle"></i> Tambah Pertanyaan - ' + tipeLabels[tipe]
        );

        $('#modalTambahPertanyaan').modal('show');
    }

    // Toggle status pertanyaan
    function toggleStatus(id, currentStatus) {
        var newStatus = currentStatus === '1' ? '0' : '1';
        var url = '{{ route("ppid.pertanyaan.updateStatus", "ID") }}'.replace('ID', id);

        if (confirm('Ubah status pertanyaan ini?')) {
            $.ajax({
                url: url,
                method: 'PATCH',
                data: {
                    ppid_status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Gagal mengupdate status.');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        }
    }

    // Hapus pertanyaan
    function deletePertanyaan(id) {
        var url = '{{ route("ppid.pertanyaan.destroy", "ID") }}'.replace('ID', id);

        if (confirm('Yakin ingin menghapus pertanyaan ini?')) {
            $.ajax({
                url: url,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus pertanyaan.');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        }
    }
</script>
@endpush
