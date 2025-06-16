@extends('layouts.dashboard_template')

@push('css')
    <style>
        /* Container for the switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            /* Lebar toggle switch */
            height: 20px;
            /* Tinggi toggle switch */
        }

        /* Hide default checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* Slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 20px;
            /* Rounded edges */
        }

        /* Slider before the switch (circle) */
        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            /* Tinggi lingkaran */
            width: 16px;
            /* Lebar lingkaran */
            border-radius: 50%;
            left: 2px;
            /* Jarak dari kiri */
            bottom: 2px;
            /* Jarak dari bawah */
            background-color: white;
            transition: .4s;
        }

        /* When the checkbox is checked */
        input:checked+.slider {
            background-color: #007bff;
            /* Warna aktif Bootstrap */
        }

        /* Move the slider when checked */
        input:checked+.slider:before {
            transform: translateX(20px);
            /* Jarak untuk geser lingkaran */
        }

        /* Rounded slider */
        .slider.round {
            border-radius: 20px;
        }

        /* Rounded slider circle */
        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endpush

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

        <div class="box box-primary">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="komentar-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>User</th>
                                <th>Komentar</th>
                                <th>Artikel</th>
                                <th>Tampilkan Komentar?</th>
                                <th>Tanggal Komentar</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var data = $('#komentar-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{!! route('informasi.komentar-artikel.getdata') !!}",
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'komentar',
                        name: 'komentar'
                    },
                    {
                        data: 'artikel',
                        name: 'artikel'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [5, 'desc']
                ]
            });

            // Handle the toggle switch change
            $('#komentar-table').on('change', '.toggle-status', function() {
                var commentId = $(this).data('id');
                var newStatus = $(this).prop('checked') ? 'enable' : 'disable';

                $.ajax({
                    url: "{{ route('informasi.komentar-artikel.updateStatus') }}", // Ganti dengan URL yang sesuai untuk update status
                    method: 'POST',
                    data: {
                        id: commentId,
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Status updated successfully!');
                        // Optionally, reload the DataTable
                        data.ajax.reload();
                    },
                    error: function(xhr) {
                        console.log('Failed to update status');
                    }
                });
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
