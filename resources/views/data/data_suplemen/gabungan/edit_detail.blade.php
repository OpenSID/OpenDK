@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.data-suplemen.index') }}">Daftar Data Suplemen</a></li>
            <li><a href="{{ route('data.data-suplemen.show', $suplemen->id) }}">Data Suplemen {{ $suplemen->nama }}</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Ups!</strong> Ada beberapa masalah dengan masukan Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- form start -->
                    {!! html()->form('post', route('data.data-suplemen.updatedetail', $anggota->id))->id('form-suiplemen-gabungan')->class('form-horizontal form-label-left')->open() !!}

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                                <tr>
                                    <th class="col-md-2">Nama</th>
                                    <td>: {{ $suplemen->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Sasaran</th>
                                    <td>: {{ $sasaran[$suplemen->sasaran] }}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>: {{ $suplemen->keterangan }}</td>
                                </tr>
                            </table>
                        </div>
                        <hr>
                        <legend>Daftar Anggota Suplemen</legend>

                        {{ method_field('PUT') }}
                        @include('flash::message')

                        {{ html()->hidden('suplemen_id', $suplemen->id) }}

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama
                                {{ config('setting.sebutan_desa') }}</label>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                @include('layouts.fragments.select-desa', ['selectedOption' => $selectedDesaId])
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penduduk_id">{{ $suplemen->sasaran == 2 ? 'Nama Kepala Keluarga' : 'Nama Penduduk' }}</label>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="penduduk_id_gabungan" id="penduduk_id_gabungan" class="form-control" required>
                                    @if ($penduduk)
                                        <option value="{{ $penduduk->id }}" selected>{{ trim($penduduk->nama . ' - ' . ($penduduk->nik ?? ''), ' -') }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! html()->textarea('keterangan')->class('textarea')->placeholder('Keterangan')->value(old('keterangan', $anggota->keterangan))->style('width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;') !!}
                            </div>
                        </div>
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

@include('partials.asset_select2')

@push('scripts')
    <script>
        $(function() {
            var $penduduk = $('#penduduk_id_gabungan');
            $('#list_desa').select2({                
                width: '100%',
            })

            $penduduk.select2({
                placeholder: 'Pilih Penduduk',
                width: '100%',
                minimumInputLength: 0,
                allowClear: true,
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}/api/v1/opendk/sync-penduduk-opendk`,
                    dataType: 'json',
                    delay: 400,
                    headers: {
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`,
                        "Accept": "application/ld+json"
                    },
                    data: function(params) {
                        var data = {
                            'filter[kode_kecamatan]': {{ str_replace('.', '', $profil->kecamatan_id) }},
                            'filter[kode_desa]': $('#list_desa').val(),
                            'filter[status_dasar]': 1,
                            'filter[search]': params.term,
                            'page[size]': 20,
                            'page[number]': params.page || 1,
                        };

                        @if ((int) $suplemen->sasaran !== 1)
                            data['filter[kk_level]'] = 1;
                        @endif

                        return data;
                    },
                    processResults: function(response, params) {
                        params.page = params.page || 1;

                        var total = (response.meta && response.meta.pagination && response.meta.pagination.total) || 0;

                        return {
                            results: $.map(response.data || [], function(item) {
                                var attributes = item.attributes || {};

                                return {
                                    id: item.id,
                                    text: attributes.nik ? attributes.nama + ' - ' + attributes.nik : attributes.nama,
                                };
                            }),
                            pagination: {
                                more: params.page * 20 < total
                            }
                        };
                    },
                    cache: true
                }
            });

            function setPendudukEnabled(enabled) {
                var instance = $penduduk.data('select2');
                $penduduk.prop('disabled', !enabled);

                if (instance) {
                    instance._syncAttributes();
                }
            }

            function hasDesa() {
                var desa = $('#list_desa').val();

                return !!desa && desa !== 'Semua';
            }

            $('#list_desa').on('change', function() {
                $penduduk.val(null).trigger('change');
                setPendudukEnabled(hasDesa());
            });

            if (hasDesa()) {
                setPendudukEnabled(true);
            }
        });
    </script>
@endpush
