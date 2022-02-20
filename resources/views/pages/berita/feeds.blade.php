<div id="feeds">
	<div class="post clearfix">
    @forelse ($feeds as $item)
    <div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">
      <div class="row">
        <div class="col-sm-4">
          <img class="img-responsive" src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
        </div>
        <div class="col-sm-8">
          <h5 style="margin-top: 5px; text-align: justify;"><b><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></b></h5>
					<p style="font-size:11px;">
						<i class="fa fa-calendar"></i>&ensp;{{ $item['date'] }}&ensp;|&ensp;
						<i class="fa fa-user"></i>&ensp;{{ $item['author'] }}&ensp;|&ensp;
						<i class="fa fa-globe"></i>&ensp;{{ $item['nama_desa'] }}
					</p>
          <p style="text-align: justify;">{!! $item['description'] !!}</p>
          <a href="{{ $item['link'] }}" class="btn btn-sm btn-primary" target="_blank">Selengkapnya</a>
        </div>
      </div>
    </div>
    @empty
      <div class="callout callout-info">
        <p class="text-bold">Tidak ada berita desa yang ditampilkan!</p>
      </div>
    @endforelse
		<div class="text-center">
			{{ $feeds->links() }}
		</div>
  </div>
</div>

@include('partials.asset_select2')

@push('scripts')
<script type="text/javascript">
	$(document).ready(function () {
		var ajax_artikel = function () {
        $.ajax({
          url: "{{ route('filter-berita-desa') }}",
          type: 'get',
          dataType:'json',
          data:$("#form_filter").serialize(),
          success: function(data){
						$("#feeds").html(data.html);
          },
          error: function (jqXhr, textStatus, errorMessage) { // error callback 
            $("#feeds").html('Error: ' + errorMessage);
          }
      });
      event.preventDefault();
    }

		$( '#list_desa' ).select2();
		$( "#list_desa" ).change(function() {
			$( "#form_filter" ).submit();
		});

		$(document).on('click', '.pagination a', function(event){
      event.preventDefault(); 
      var page = $(this).attr('href').split('page=')[1];
      $('input[name="page"]').val(page);
      ajax_artikel()
    });

		$(document).on('submit', '#form_filter', function(event){
			ajax_artikel();
			event.preventDefault();
		})
	});
</script>
@endpush