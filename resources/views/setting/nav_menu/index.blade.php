@extends('layouts.dashboard_template')

@push('css')
    <style>
        .margin-50 {
            margin-left: 50px;
        }

        .bolt {
            font-weight: bold;
        }

        .btn-dark {
            color: #fff;
            background-color: #343a40;
            border-color: #343a40;
        }

        .btn-dark:hover {
            color: #fff;
            background-color: #23272b;
            border-color: #1d2124;
        }

        .btn-dark:focus,
        .btn-dark.focus {
            color: #fff;
            background-color: #23272b;
            border-color: #1d2124;
            box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.5);
        }

        .btn-dark:active,
        .btn-dark.active,
        .open>.dropdown-toggle.btn-dark {
            color: #fff;
            background-color: #1d2124;
            border-color: #171a1d;
        }

        .btn-dark:active:hover,
        .btn-dark.active:hover,
        .open>.dropdown-toggle.btn-dark:hover {
            color: #fff;
            background-color: #171a1d;
            border-color: #0c0d0e;
        }

        .btn-dark.disabled,
        .btn-dark:disabled {
            color: #fff;
            background-color: #343a40;
            border-color: #343a40;
        }

        .btn-dark:not(:disabled):not(.disabled):active,
        .btn-dark:not(:disabled):not(.disabled).active,
        .show>.btn-dark.dropdown-toggle {
            background-color: #1d2124;
            border-color: #171a1d;
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

        <div class="row">
            {!! Form::open(['route' => 'setting.navmenu.store', 'id' => 'frmEdit']) !!}
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">Sumber Menu URL</div>
                    <div class="box-body">
                        @include('setting.nav_menu.field')
                    </div>
                    <div class="box-footer">
                        {!! Form::button('<i class="fa fa-save"></i> Simpan', [
                            'type' => 'button',
                            'class' => 'btn btn-primary btn-sm',
                            'id' => 'btnUpdate',
                        ]) !!}
                        {!! Form::button('<i class="fa fa-plus-square"></i> Tambah', [
                            'type' => 'button',
                            'class' => 'btn btn-success btn-sm',
                            'id' => 'btnAdd',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">Struktur Menu</div>
                            <div class="box-body">
                                <ul id="myEditor" class="sortableLists list-group"></ul>
                                <div class="hide">
                                    {!! Form::textarea('json_menu', null, ['hidden', 'rows' => 1]) !!}
                                </div>
                            </div>
                            <div class="box-footer">
                                {!! Form::button('<i class="fa fa-times"></i> Batal', [
                                    'type' => 'button',
                                    'class' => 'btn btn-danger btn-sm',
                                ]) !!}
                                {!! Form::button('<i class="fa fa-save"></i> Simpan', ['type' => 'submit', 'class' => 'btn btn-primary btn-sm']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
@endsection

@push('scripts')
    <script defer src="{{ asset('bower_components/menu-editor/bootstrap-iconpicker.min.js') }}"></script>
    <script defer src="{{ asset('bower_components/menu-editor/jquery-menu-editor.js') }}"></script>
    <script>
        $(document).ready(function() {
            var arrayjson = {!! $nav_menus !!} || [];

            var iconPickerOptions = {
                searchText: "Buscar...",
                labelHeader: "{0}/{1}"
            };

            var sortableListOptions = {
                placeholderCss: {
                    'background-color': "#cccccc"
                }
            };

            var editor = new MenuEditor('myEditor', {
                listOptions: sortableListOptions,
                iconPicker: iconPickerOptions
            });

            editor.setForm($('#frmEdit'));
            editor.setUpdateButton($('#btnUpdate'));
            editor.setData(arrayjson);

            $('#btnOutput').on('click', function() {
                var str = editor.getString();
                $("#out").text(str);
            });

            $("#btnUpdate").click(function() {
                editor.update();
            });

            $('#btnAdd').click(function() {
                let sourceLink = $('#sourceLink');
                let sourceHalaman = $('#sourceHalaman');
                let sourceKategori = $('#sourceKategori');
                let sourceModul = $('#sourceModul');
                let sourceDokumen = $('#sourceDokumen');

                let source = '';
                if (sourceLink.is(':checked')) source = sourceLink.val();
                else if (sourceHalaman.is(':checked')) source = sourceHalaman.val();
                else if (sourceKategori.is(':checked')) source = sourceKategori.val();
                else if (sourceModul.is(':checked')) source = sourceModul.val();
                else if (sourceDokumen.is(':checked')) source = sourceDokumen.val();

                editor.add(source);
            });

            $('#frmEdit').bind('reset', function(e) {
                $('select[name=sourcelist]').hide();
                $('input[name=href]').show();
            });

            $('#frmEdit').submit(function(e) {
                var str = editor.getString();
                if (str == '[]') {
                    e.preventDefault();
                    return;
                }
                $('#frmEdit').find('textarea[name=json_menu]').val(str);
                return true;
            });

            $('button.reload').click(function() {
                window.location.reload();
            });
        });
    </script>
@endpush
