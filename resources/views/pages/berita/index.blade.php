<div id="kecamatan">
  <div class="post clearfix">
    @forelse ($artikel as $item)
    <div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">
      <div class="row">
        <div class="col-sm-4">
          <img class="img-responsive" src="{{ is_img($item->gambar) }}" alt="{{ $item->slug }}">
        </div>
        <div class="col-sm-8">
          <h5 style="margin-top: 5px; text-align: justify;"><b><a href="berita/{{ $item->slug }}">{{ $item->judul }}</a></b></h5>
          <p style="font-size:11px;"><i class="fa fa-calendar"></i>&ensp;{{ $item->created_at->format('d M Y') }}&ensp;|&ensp;<i class="fa fa-user"></i>&ensp;Administrator</p>
          <p style="text-align: justify;">{{ strip_tags(substr($item->isi, 0, 250)) }}...</p>
          <a href="berita/{{ $item->slug }}" class="btn btn-sm btn-primary" target="_blank">Selengkapnya</a>
        </div>
      </div>
    </div>
    @empty
      <div class="callout callout-info">
        <p class="text-bold">Tidak ada berita kecamatan yang ditampilkan!</p>
      </div>
    @endforelse
    <div class="text-center">
      {{ $artikel->links() }}
    </div>
  </div>
</div>

@push('scripts')
<script type="text/javascript">
  $(function ($) {
    $(document).on('submit', '#form_berita_cari', function(event){ 
      event.preventDefault();
      ajax_artikel()
    })

    $(document).on('click', '.pagination a', function(event){
      event.preventDefault(); 
      var page = $(this).attr('href').split('pageArtikel=')[1];
      $('input[name="pageArtikel"]').val(page);
      ajax_artikel()
    });

    var ajax_artikel = function () {
        $('#berita-preload').show()
        $.ajax({
          url: "{{ route('berita.filter') }}",
          type: 'get',
          dataType:'json',
          data:$("#form_berita_cari").serialize(),
          success: function(data){
            $('#berita-preload').hide();
            $("#kecamatan").html(data.html);
          },
          error: function (jqXhr, textStatus, errorMessage) { // error callback 
            $("#kecamatan").html('Error: ' + errorMessage);
          }
      });
      event.preventDefault();
    }
  });
</script>
@endpush