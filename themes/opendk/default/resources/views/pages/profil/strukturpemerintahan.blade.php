@extends('layouts.app')
@section('title', 'Struktur Pemerintahan')
@section('content')
    <div class="col-md-8">
        <div class="box box-warning">
            <div class="box-header with-border">
                <div class="box-header with-border text-bold">
                    <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> STRUKTUR PEMERINTAHAN {{ strtoupper(config('profil.sebutan_wilayah')) }} <span id="nama-kecamatan"></span></h3>
                </div>
            </div>
            <div class="box-body">
                <!-- The Modal -->
                <img id="struktur-organisasi-img" style="width:97%;" src="" class="hidden">
                <!-- The Modal -->
                <div id="pengurus-container">
                    <!-- Pengurus will be loaded here via JavaScript -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('forms.image-modal')
    <script>
        $(function(){
            $(document).on('websiteDataLoaded', function(event, websiteData) {
                var profile = null;
                let pengurus = null, pengurusHtml = '';
                if (websiteData.profile) {
                    profile = websiteData.profile;
                    $('#nama-kecamatan').text(profile.nama_kecamatan ? profile.nama_kecamatan.toUpperCase() : '');
                    
                    // Set the structure organization image
                    if(profile.file_struktur_organisasi_path) {
                        $('#struktur-organisasi-img').attr('src', profile.file_struktur_organisasi_path).removeClass('hidden');
                    }
                }
                
                if (websiteData.pengurus) {
                    pengurus = websiteData.pengurus;
                    
                    pengurus.forEach(function(item) {
                        const fotoPath = item.foto ? (item.foto.startsWith('http') ? item.foto : '{{ asset("") }}' + item.foto) : '{{ asset("img/no-profile.png") }}';
                        const jabatanNama = item.jabatan && typeof item.jabatan === 'object' ? item.jabatan.nama : (item.jabatan_nama || '');
                        
                        pengurusHtml += '<div class="col-md-3 col-sm-6">' +
                            '<div class="pad text-bold bg-white" style="text-align:center;">' +
                                '<img id="myImg" src="' + fotoPath + '" width="auto" height="120px" class="img-user" style="object-fit: contain;">' +
                            '</div>' +
                            '<div class="box-header text-center  with-border bg-blue">' +
                                '<p class="box-title text-bold text-small" data-toggle="tooltip" data-placement="top" style="font-size: 12px;">' +
                                    (item.namaGelar || item.nama || '') + '<br /> <span style="font-size: 10px;color: #ecf0f5;">' + jabatanNama + '</span></p>' +
                            '</div>' +
                        '</div>';
                    });
                }
                
                $('#pengurus-container').html(pengurusHtml);
            });
        })
    </script>
@endpush
