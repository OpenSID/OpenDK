@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>{{ $page_title }} <small>{{ $page_description }}</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>

    <section class="content container-fluid">
        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                <a href="{{ route('ppid.jenis-dokumen.create') }}" class="btn btn-primary btn-sm" title="Tambah Data">
                    <i class="fa fa-plus"></i>&ensp;Tambah
                </a>
                <button type="button" class="btn btn-danger btn-sm" id="btn-hapus-terpilih" style="display:none;">
                    <i class="fa fa-trash"></i>&ensp;Hapus Terpilih
                </button>
            </div>
            <div class="box-body">
                <!-- Filter Dropdown status -->
                <div class="form-group row">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="row">
                            <div class="col-md-4 pr-md-1">
                                {!! html()->select('status')->options([
                                        1 => 'Aktif',
                                        0 => 'Tidak Aktif',
                                    ])->class('form-control filter-input')->placeholder('Semua')->id('filter_status') !!}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="data_jenis_dokumen">
                        <thead>
                            <tr>
                                <th style="width: 10px;">
                                    <input type="checkbox" id="check-all">
                                </th>
                                <th style="width: 10px;">No</th>
                                <th style="width: 20px;">#</th>
                                <th style="width: 150px;">Aksi</th>
                                <th>Nama Jenis Dokumen</th>
                                <th>Deskripsi</th>
                                <th style="width: 50px;">Icon</th>
                                <th style="width: 30px;">Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Konfirmasi Bulk Delete -->
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Konfirmasi Hapus</h4>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong id="delete-count">0</strong> data yang dipilih?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirm-bulk-delete">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('partials.asset_datatables')
@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var isInitialLoad = true;
            var table = $('#data_jenis_dokumen').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('ppid.jenis-dokumen.getdata') !!}",
                    data: function(d) {
                        d.status = $('#filter_status').val() || '';
                    }
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-id', data.id).addClass('row-sort');
                },
                order: [],
                columnDefs: [{
                        targets: [0, 1, 2, 3, 6, 7],
                        orderable: false
                    },
                    {
                        targets: [4, 5],
                        orderable: true
                    }
                ],
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        class: 'text-center'
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'drag_handle',
                        name: 'drag_handle',
                        class: 'text-center'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-left'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi'
                    },
                    {
                        data: 'icon',
                        name: 'icon',
                        class: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        class: 'text-center'
                    },
                ]
            });

            // --- Helper: Tampilkan Notifikasi ---
            function showAlert(type, message, title = 'Sukses!') {
                $('#notifikasi-ajax').remove();
                var icon = type === 'success' ? 'fa-check' : 'fa-ban';
                var alertClass = 'alert-' + type;

                var alertHtml = `
            <div id="notifikasi-ajax" class="alert ${alertClass} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa ${icon}"></i> ${title}</h4>
                <p>${message}</p>
            </div>`;

                $(alertHtml).hide().prependTo('.box-body').fadeIn('slow');

                if (type === 'success') {
                    setTimeout(() => {
                        $('#notifikasi-ajax').fadeOut('slow');
                    }, 3000);
                }
            }

            // --- Checkbox Logic ---
            $('#check-all').on('change', function() {
                $('.checkbox-item:not(:disabled)').prop('checked', $(this).prop('checked'));
                toggleBulkDeleteButton();
            });

            $(document).on('change', '.checkbox-item', function() {
                var allChecked = $('.checkbox-item:not(:disabled)').length === $('.checkbox-item:checked')
                    .length;
                $('#check-all').prop('checked', allChecked);
                toggleBulkDeleteButton();
            });

            function toggleBulkDeleteButton() {
                $('#btn-hapus-terpilih').toggle($('.checkbox-item:checked').length > 0);
            }

            // --- Bulk Delete ---
            $('#btn-hapus-terpilih').on('click', function() {
                var count = $('.checkbox-item:checked').length;
                $('#delete-count').text(count);
                $('#bulkDeleteModal').modal('show');
            });

            $('#confirm-bulk-delete').on('click', function() {
                var selectedIds = $('.checkbox-item:checked').map(function() {
                    return $(this).val();
                }).get();
                var btn = $(this);

                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menghapus...');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('ppid.jenis-dokumen.bulk-delete') }}",
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#bulkDeleteModal').modal('hide');
                        if (response.success) {
                            showAlert('success', response.message);
                            table.ajax.reload();
                        } else {
                            showAlert('danger', response.message, 'Gagal!');
                        }
                    },
                    error: function() {
                        $('#bulkDeleteModal').modal('hide');
                        showAlert('danger', 'Terjadi kesalahan sistem', 'Gagal!');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('Hapus');
                    }
                });
            });

            // --- Sorting & Drag-Drop ---
            table.on('order.dt', function() {
                if (isInitialLoad) {
                    isInitialLoad = false;
                    return;
                }
                if (table.order().length > 0) {
                    disableDragDrop();
                    showSortingNotification();
                }
            });

            function disableDragDrop() {
                if ($("#data_jenis_dokumen tbody").hasClass('ui-sortable')) {
                    $("#data_jenis_dokumen tbody").sortable("disable");
                }
                $('.handle').css({
                    'cursor': 'not-allowed',
                    'opacity': '0.3'
                }).attr('title', 'Refresh untuk urutan manual');
            }

            function showSortingNotification() {
                if (!$('.sorting-notification').length) {
                    var notification = `
                <div class="alert alert-warning alert-dismissible sorting-notification" style="border-left: 5px solid #e08e0b;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> Mode Sortir Aktif!</h4>
                    Fitur <strong>Drag & Drop</strong> dimatikan. 
                    <a href="javascript:location.reload()" class="alert-link" style="text-decoration: underline;">Klik di sini</a> untuk reset.
                </div>`;
                    $(notification).hide().prependTo('.box-body').fadeIn('slow');
                }
            }

            $("#data_jenis_dokumen tbody").sortable({
                handle: '.handle',
                placeholder: "ui-state-highlight",
                axis: "y",
                update: function() {
                    var order = $('#data_jenis_dokumen tbody tr').map(function(i) {
                        return {
                            id: $(this).data('id'),
                            position: i + 1
                        };
                    }).get();

                    $.ajax({
                        type: "POST",
                        url: "{{ route('ppid.jenis-dokumen.update-order') }}",
                        data: {
                            order: order,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(r) {
                            r.success ? showAlert('success', r.message) : showAlert(
                                'danger', r.message, 'Gagal!');
                        },
                        error: function() {
                            showAlert('danger', 'Gagal memperbarui urutan', 'Gagal!');
                            table.ajax.reload();
                        }
                    });
                }
            });

            $('.filter-input').change(function() {
                table.draw();
            });

            table.on('draw', function() {
                $('#check-all').prop('checked', false);
                toggleBulkDeleteButton();
                if (!isInitialLoad && table.order().length > 0) disableDragDrop();
            });
        });
    </script>

    <style>
        /* Styling untuk drag & drop */
        .ui-state-highlight {
            height: 50px;
            background-color: #f0f0f0;
            border: 2px dashed #ccc;
        }

        .handle {
            cursor: move;
            transition: color 0.2s;
        }

        .handle:hover {
            color: #3c8dbc !important;
        }

        .row-sort {
            transition: background-color 0.2s;
        }

        .row-sort:hover {
            background-color: #f9f9f9;
        }

        /* Styling untuk checkbox */
        #check-all {
            cursor: pointer;
        }

        .checkbox-item {
            cursor: pointer;
        }

        .checkbox-item:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
    </style>

    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
    @include('forms.lock-modal')
    @include('forms.unlock-modal')
@endpush
