<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <label>Desa</label>
            <select class="form-control" id="list_desa">
                
            </select>
        </div>
    </div>
</div>
@include('partials.asset_select2')
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#list_desa').select2({
                ajax: {
                    url: `{{ $settings['api_server_database_gabungan'] ?? '' }}{{ '/api/v1/desa?' .
                        http_build_query([
                            'filter[kode_kecamatan]' => str_replace('.', '', $profil->kecamatan_id),
                        ]) }}`,
                    headers: {
                        "Accept": "application/ld+json",
                        "Content-Type": "text/json; charset=utf-8",
                        "Authorization": `Bearer {{ $settings['api_key_database_gabungan'] ?? '' }}`
                    },
                    method: 'get',
                    dataType: 'json',
                    delay: 400,
                    data: function(params) {
                        return {                            
                            "filter[search]": params.term,
                            "page[number]": params.page,
                            "fields[config]": "id,nama_desa",                            
                        };
                    },
                    processResults: function(response, params) {
                        params.page = params.page || 1;

                        return {
                            results: $.map(response.data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.attributes.nama_desa,
                                }
                            }),
                            pagination: response.pagination
                        };
                    },
                    cache: true
                },
                placeholder: 'Pilih Desa',
                minimumInputLength: 2,
                allowClear: true,
                language: {
                    // You can find all of the options in the language files provided in the
                    // build. They all must be functions that return the string that should be
                    // displayed.
                    inputTooShort: function () {
                        return "Masukkan minimal 2 karakter";
                    }
                }

            });            
        });
    </script>    
@endpush
