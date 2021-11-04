<script>
  $(function () {

    const host = '<?= config('app.host_pantau'); ?>';
    const token = '<?= config('app.token_pantau'); ?>';

    $('#list_provinsi').select2({
      ajax: {
        url: host + 'wilayah/list_wilayah?token=' + token,
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
                id: item.kode_prov,
                text: item.nama_prov,
              }
            }),
            pagination: response.pagination
          };
        },
        cache: true
      }
    });
    
    list_kabupaten();

    $('#list_provinsi').change(function () {
      $("#provinsi_id").val($('#list_provinsi').val());
      $("#nama_provinsi").val($('#list_provinsi option:selected').text());

      list_kabupaten();
    });

    list_kecamatan();

    $('#list_kabupaten').change(function () {
      $("#kabupaten_id").val($('#list_kabupaten').val());
      $("#nama_kabupaten").val($('#list_kabupaten option:selected').text());

      list_kecamatan();
    });

    $('#list_kecamatan').change(function () {
      $("#kecamatan_id").val($('#list_kecamatan').val());
      $("#nama_kecamatan").val($('#list_kecamatan option:selected').text());
    });

    function list_kabupaten() {
      
      $('#list_kabupaten').select2({
        ajax: {
          url: host + 'wilayah/list_wilayah?token=' + token + '&kode=' + $('#list_provinsi').val(),
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
                  id: item.kode_kab,
                  text: item.nama_kab,
                }
              }),
              pagination: response.pagination
            };
          },
          cache: true
        }
      });
    }

    function list_kecamatan() {
      
      $('#list_kecamatan').select2({
        ajax: {
          url: host + 'wilayah/list_wilayah?token=' + token + '&kode=' + $('#list_kabupaten').val(),
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
                  id: item.kode_kec,
                  text: item.nama_kec
                }
              }),
              pagination: response.pagination
            };
          },
          cache: true
        }
      });
      
    }
  })
</script>