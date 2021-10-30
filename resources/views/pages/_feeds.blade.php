@forelse ($articles as $item)
<?php $path = \Storage::url('artikel/'.$item->image); ?>

<div class="card flex-md-row mb-4 box-shadow h-md-250">
   <div class="card-body d-flex flex-column align-items-start">
      <div class="card-horizontal">
         <div class="img-square-wrapper">
            <a href="berita/{{ $item->slug }}">
            <img class="" src="{{ url($path) }}" alt="Card image cap" style="width: 235px;height: 150px;object-fit: cover;">
            </a>
         </div>
         <div class="card">
			<div class="card-body">
				<a href="berita/{{ $item->slug }}">
				   <h4 class="card-title text-black" style="margin-top: -5px;">{{ $item->title }}</h4>
				</a>
				<span class="card-text">{!! strip_tags(substr($item->description,0,250)) !!}</span>
			 </div>
		 </div>
		 
      </div>
   </div>
</div>

<br>
@empty
<div class="text-center">
   <p class="text-bold">Tidak ada berita yang ditampilkan!</p>
</div>
@endforelse
{{ $articles->links() }}