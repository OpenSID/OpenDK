@extends('layouts.app')
@section('title', 'Galeri')

@push('css')
<style>
    .image-container {
        width: 100%;
        padding-top: 100%;
        /* Menyusun rasio aspek 1:1 (tinggi = lebar) */
        position: relative;
        margin-bottom: 15px;
        /* Sesuaikan dengan jarak yang diinginkan */
    }

    .image-container img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Menjaga proporsi gambar dengan crop jika perlu */
    }
</style>
@endpush

@section('content')
<div class="col-md-8">
    <div class="box box-warning">
        <div class="box-header with-border">
            <div class="box-header with-border text-bold">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> Galeri :
                    <span id="galeri-judul">Loading...</span>
                </h3>
            </div>
        </div>
        <div class="box-body">
            <div id="galeri-images-container">
                <!-- Loading indicator -->
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                    <p>Loading galeri...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){
        // Function to load galeri detail from API
        function loadGaleriDetail() {
            // Make API call to get galeri by slug
            $.ajax({
                url: '{!! $urlApi !!}/galeri?filter[status]=1&filter[slug]={!! $slug !!}',
                method: 'GET',
                success: function(response) {
                    if (response.data && response.data.length > 0) {
                        var galeri = response.data[0].attributes;
                        
                        // Update the title
                        $('#galeri-judul').text(galeri.judul ? galeri.judul.toUpperCase() : '');
                        
                        // Generate images HTML
                        var imagesHtml = '';
                        if (galeri.gambar && Array.isArray(galeri.gambar)) {
                            galeri.gambar.forEach(function(item) {
                                var imagePath = '{{ asset("") }}storage/publikasi/galeri/' + item;
                                imagesHtml += '<div class="col-md-6 mt-2">' +
                                    '<div class="image-container">' +
                                        '<img id="myImg" class="img-fluid" src="' + imagePath + '" alt="Image">' +
                                    '</div>' +
                                '</div>';
                            });
                        } else {
                            imagesHtml = '<div class="col-12"><p>Tidak ada gambar yang ditampilkan!</p></div>';
                        }
                        
                        // Update the container with images
                        $('#galeri-images-container').html(imagesHtml);
                        
                        // Reinitialize image modal functionality if it exists
                        initializeImageModal();
                    } else {
                        $('#galeri-judul').text('Galeri Tidak Ditemukan');
                        $('#galeri-images-container').html('<div class="col-12"><p>Galeri tidak ditemukan!</p></div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading galeri detail:', error);
                    $('#galeri-judul').text('Gagal Memuat Galeri');
                    $('#galeri-images-container').html('<div class="col-12"><p>Gagal memuat galeri. Silakan coba lagi nanti.</p></div>');
                }
            });
        }
        
        // Load galeri detail on page load
        loadGaleriDetail();
        
        // Initialize image modal functionality
        function initializeImageModal() {
            // If there's a modal functionality, reinitialize it here
            // This would depend on what's in 'forms.image-modal'
            $('#myImg').on('click', function() {
                var modal = document.getElementById("myModal");
                var modalImg = document.getElementById("img01");
                var captionText = document.getElementById("caption");
                
                if (modal && modalImg) {
                    modal.style.display = "block";
                    modalImg.src = this.src;
                    captionText.innerHTML = this.alt;
                }
            });
        }
    });
</script>
@include('forms.image-modal')
@endpush