@extends('layouts.app')
@section('title', 'Visi dan Misi')
@section('content')
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> VISI DAN MISI {{ strtoupper($sebutan_wilayah) }} {{ strtoupper($profil->nama_kecamatan) }}</h3>
            </div>
            <div class="box-body" id="visimisi-container">
                
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(function(){
        $(document).on('websiteDataLoaded', function(event, websiteData) {
            var profile = null;            
            if (websiteData.profile) {
                profile = websiteData.profile;                    
            }

            if (!profile) return;            

            $('#visimisi-container').html(`<h3 class="box-title text-bold text-center">VISI</h3>
                <p>${profile.visi}</p>
                <hr>
                <h3 class="box-title text-bold text-center">MISI</h3>
                <p>${profile.misi}</p>`);
        });
    })
</script>
@endpush
