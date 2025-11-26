@extends('layouts.app')
@section('title', 'Sejarah')
@section('content')
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border text-bold">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> SEJARAH {{ strtoupper($sebutan_wilayah) }} {{ strtoupper($profil->nama_kecamatan) }}</h3>
            </div>
            <div class="box-body">
                <center>
                    <img class="img-circle" style="display:block;margin:auto" src="{{ is_logo($profil->file_logo) }}">
                </center>
                <p id="sejarah-container"></p>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(function(){
        $(document).on('websiteDataLoaded', function(event, websiteData) {
            var profile = null;  
            let sejarah = null;          
            if (websiteData.profile) {
                profile = websiteData.profile;                    
                sejarah = profile.data_umum.sejarah;
            }      
            
            if (!sejarah) return;

            $('#sejarah-container').html(sejarah);
        });
    })
</script>
@endpush
