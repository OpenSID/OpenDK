<div id="feeds">
	<div class="post clearfix">
    @forelse ($feeds as $item)
    <div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">
      <div class="row">
        <div class="col-sm-4">
          <img class="img-responsive" src="{{ get_tag_image($item['description']) }}" alt="{{ $item['title'] }}">
        </div>
        <div class="col-sm-8">
          <h5 style="margin-top: 5px; text-align: justify;"><b><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></b></h5>
					<p style="font-size:11px;">
						<i class="fa fa-calendar"></i>&ensp;{{ \Carbon\Carbon::parse($item['date'])->translatedFormat('Y H:i') }}&ensp;|&ensp;
						<i class="fa fa-user"></i>&ensp;{{ $item['author'] ?? 'Administrator'}}&ensp;|&ensp;
						<i class="fa fa-globe"></i>&ensp;{{ $item['nama_desa'] }}
					</p>
          <p style="text-align: justify;">{{ strip_tags(substr($item['description'], 0, 250)) }}...</p>
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
		$( '#list_desa' ).select2();
		$( "#list_desa" ).change(function() {
			$( "#form_filter" ).submit();
		});

		$(function($){
			$(document).on('submit', '#form_filter', function(event){
				$.ajax({
					url: "{{ route('feeds.filter') }}",
					type: 'get',
					dataType:'json',
					data:$("#form_filter").serialize(),
					success: function(data){
						$("#feeds").html(data.html);
					}
				});
				event.preventDefault();
			})
		})
	});
</script>
@endpush