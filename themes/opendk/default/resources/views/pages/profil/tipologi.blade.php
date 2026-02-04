@extends('layouts.app')
@section('title', 'Tipologi')
@section('content')
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border text-bold">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> TIPOLOGI {{ strtoupper($sebutan_wilayah) }} {{ strtoupper($profil->nama_kecamatan) }}</h3>
            </div>
            <div class="box-body">
                <p id="tipologi-container">
                <center>
                    <img class="img-circle" style="display:block;margin:auto" src="{{ is_logo($profil->file_logo) }}">
                </center>
                <p> {!! $profil->dataumum->tipologi !!}</p>

                </p>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(function(){
        $(document).on('websiteDataLoaded', function(event, websiteData) {
            var profile = null;  
            let tipologi = null;          
            if (websiteData.profile) {
                profile = websiteData.profile;                    
                tipologi = profile.data_umum.tipologi;
            }      
            
            if (!tipologi) return;

            $('#tipologi-container p').html(tipologi);
            $('#tipologi-container img.img-circle').attr('src', profile.file_logo)
        });
    })
</script>
@endpush