<div class="box-header with-border text-center  bg-blue">
    <h2 class="box-title text-bold">Sinergi Program</h2>
</div>
<div class="row" style="margin: 0">
    @foreach ($sinergi as $key => $data)
        <div class="col-md-6" style="padding: 4px;">
            <a href="{{ $data->url }}" rel="noopener noreferrer" target="_blank">
                <img src="{{ asset($data->gambar) }}" class="logo-sinergi-program" alt="Sinergi Program Image">
            </a>
        </div>
    @endforeach
</div>
