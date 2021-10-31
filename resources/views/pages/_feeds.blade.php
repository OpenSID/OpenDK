<div class="fat-arrow">
	<div class="flo-arrow"><i class="fa fa-globe fa-lg fa-spin"></i></div>
</div>
<form class="form-horizontal" id="form_filter" method="get" action="{{ route('feeds.filter') }}">
	<div class="page-header" style="margin:0px 0px;">
		<span style="display: inline-flex; vertical-align: middle;"><strong class="">Berita Desa</strong></span>
	</div>
	<div class="page-header" style="margin:0px 0px; padding: 0px;">
		<select class="form-control" id="list_desa" name="desa" style="width: auto;">
			<option value="ALL">Semua Desa</option>
			@foreach($list_desa as $desa)
					<option value="{{$desa->desa_id}}" <?php $cari_desa == $desa->desa_id && print('selected') ?>>{{$desa->nama}} </option>
			@endforeach
		</select>
		<div class="input-group input-group-sm" style="display: inline-flex; float: right; padding: 5px;">
			<input class="form-control" style="width: 200px; height: auto;" type="text" name="cari" placeholder="Ceri berita" value="{{$cari}}"/>
			<button type="submit" class="btn btn-info btn-block" style="width: auto;">
				<i class="fa fa-search"></i>
			</button>
		</div>
	</div>
</form>
<div id="feeds">
	@forelse ($feeds as $item)
	<div class="card flex-md-row mb-4 box-shadow h-md-250">
    <div class="card-body d-flex flex-column align-items-start">
      <div class="card-horizontal">
        <div class="img-square-wrapper">
          <a href="{{ $item['link'] }}">
            <img class="" src="{{ get_tag_image($item['description']) }}" alt="Card image cap" style="width: 235px;height: 150px;object-fit: cover;">
          </a>
        </div>
        <div class="card">
          <div class="card-body">
            <a href="{{ $item['link'] }}">
              <h4 class="card-title text-black" style="margin-top: -5px;">{{ strtoupper($item['title']) }}</h4>
            </a>
            <p class="card-text mb-auto" style=" text-align: justify;">{{ strip_tags(substr($item['description'], 0, 250)) }}</p>
            <a href="{{ $item['link'] }}" target="_blank">Selengkapnya</a>
          </div>
        </div>
      </div>
    </div>
  </div>
	<br>
	@empty
		<div class="callout callout-info">
			<p class="text-bold">Tidak ada berita desa yang ditampilkan!</p>
		</div>
	@endforelse
	{{ $feeds->links() }}
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