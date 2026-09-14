@props([
    'name' => 'penduduk_id',
    'id' => 'penduduk_id_gabungan',
    'selected' => null,
    'selectedText' => null,
    'placeholder' => 'Pilih Ketua Lembaga',
    'required' => false,
    'apiServerDatabase' => null,
    'apiKeyDatabase' => null,
    'kodeKecamatan' => null,
])

@php
    $apiServerDatabase = $apiServerDatabase ?? ($settings['api_server_database_gabungan'] ?? '');
    $apiKeyDatabase = $apiKeyDatabase ?? ($settings['api_key_database_gabungan'] ?? '');
    $kodeKecamatan = $kodeKecamatan ?? (isset($profil) ? str_replace('.', '', $profil->kecamatan_id) : '');
@endphp

<div style="display: flex; flex-direction: column;">
    <select
        name="{{ $name }}"
        id="{{ $id }}"
        class="form-control select2"
        {{ $required ? 'required' : '' }}>
        <option value="">{{ $placeholder }}</option>
        @if($selected)
            <option value="{{ $selected }}" selected>{{ $selectedText }}</option>
        @endif
    </select>
</div>

@push('scripts')
    <script>
        $(function() {
            var $penduduk = $('#{{ $id }}');

            $penduduk.select2({
                placeholder: '{{ $placeholder }}',
                width: '100%',
                minimumInputLength: 0,
                allowClear: true,
                ajax: {
                    url: `{{ $apiServerDatabase }}/api/v1/opendk/sync-penduduk-opendk`,
                    dataType: 'json',
                    delay: 400,
                    headers: {
                        "Authorization": `Bearer {{ $apiKeyDatabase }}`,
                        "Accept": "application/ld+json"
                    },
                    data: function(params) {
                        return {
                            'filter[kode_kecamatan]': '{{ $kodeKecamatan }}',
                            'filter[status_dasar]': 1,
                            'filter[search]': params.term,
                            'page[size]': 20,
                            'page[number]': params.page || 1,
                        };
                    },
                    processResults: function(response, params) {
                        params.page = params.page || 1;
                        var total = (response.meta && response.meta.pagination && response.meta.pagination.total) || 0;

                        return {
                            results: $.map(response.data || [], function(item) {
                                var attributes = item.attributes || {};
                                return {
                                    id: item.id,
                                    text: attributes.nama + ' - ' + (attributes.nik || ''),
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
        });
    </script>
@endpush