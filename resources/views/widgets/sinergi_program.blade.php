<div class="box-header with-border text-center  bg-blue">
    <h2 class="box-title text-bold">Sinergi Program</h2>
</div>
<div class="row">
    @foreach($sinergi as $key => $data)
        <div class="col-md-6">
            <a href="{{ $data->url }}" rel="noopener noreferrer" target="_blank">
                <img src="{{ $data->gambar }}" alt="Sinergi Program Image" style="max-width:100%; height:auto;">
            </a>
        </div>
    @endforeach
</div>