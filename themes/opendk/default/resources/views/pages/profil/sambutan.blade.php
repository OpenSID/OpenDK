@extends('layouts.app')
@section('title', 'Sambutan')
@section('content')
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> SAMBUTAN
                    {{ strtoupper($sebutan_kepala_wilayah) }} {{ strtoupper($profil->nama_kecamatan) }}</h3>
            </div>
            <div class="box-body">
                <div class="pad text-bold bg-white" id="camat-container" style="text-align:center;">
                    
                </div>
                <hr>
                <p id="sambutan-container"></p>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(function(){
        $(document).on('websiteDataLoaded', function(event, websiteData) {
            var profile = null;  
            let sambutan = null, camatHtml;          
            if (websiteData.profile) {
                profile = websiteData.profile;                    
                sambutan = profile.sambutan;
            }      
                        
            let fotoCamat = profile.file_struktur_organisasi_path;
            camatHtml = `<img class="img-circle" style="display:block;margin:auto"
                        src="${fotoCamat}">
                    <h4 class="box-title text-bold text-center">${profile.nama_camat}</h4>
                    <h5 class="box-title text-bold text-center">{{ config('profil.sebutan_kepala_wilayah') }} ${profile.nama_kecamatan}
                    </h5>`
            $('#sambutan-container').html(sambutan);
            $('#camat-container').html(camatHtml);
        });
    })
</script>
@endpush
