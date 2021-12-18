<script>
  $(function () {

    const host = '<?= config('app.host_pantau'); ?>';
    const token = '<?= config('app.token_pantau'); ?>';
    const kode = '<?= $profil->kecamatan_id ?>';

    $('#list_desa').select2({
      ajax: {
        url: host + 'wilayah/list_wilayah?token=' + token + '&kode=' + kode,
        dataType: 'json',
        delay: 400,
        data: function(params) {
          return {
            cari: params.term,
            page: params.page || 1,
          };
        },
        processResults: function(response, params) {
          params.page = params.page || 1;

          return {
            results: $.map(response.results, function (item) {
              return {
                id: item.kode_desa,
                text: item.nama_desa,
              }
            }),
            pagination: response.pagination
          };
        },
        cache: true
      }
    });
  })
</script>