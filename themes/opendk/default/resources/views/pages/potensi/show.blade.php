@extends('layouts.app')

@section('content')
    <div class="col-md-8">
        <div class="box box-widget">                 
            <!-- /.box-header -->
            <div class="box-body">
                <div id="potensi-detail-container">
                    <!-- Loading indicator -->
                    <div class="text-center">
                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                        <p>Loading potensi...</p>
                    </div>
                </div>            
            </div>
            <!-- /.box-footer -->
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function(){
        // Function to load potensi detail from API
        function loadPotensiDetail() {
            // Make API call to get potensi by slug
            $.ajax({
                url: '{!! $urlApi !!}/potensi?filter[slug]={!! $slug !!}',
                method: 'GET',
                success: function(response) {
                    if (response.data && response.data.length > 0) {
                        var potensi = response.data[0].attributes;                                                
                        let isPdf = (potensi.mime_type === 'application/pdf' || (potensi.file_gambar && potensi.file_gambar.toLowerCase().endsWith('.pdf')) || (potensi.file_gambar_path && potensi.file_gambar_path.toLowerCase().endsWith('.pdf')));
                        let mediaHtml = isPdf
                            ? '<iframe src="' + (potensi.file_gambar_path || '') + '#toolbar=1" width="100%" height="550" style="border: 1px solid #e0e0e0; border-radius: 4px;" frameborder="0"></iframe>'
                            : '<img src="' + (potensi.file_gambar_path || "{{ asset('/img/no-image.png') }}") + '" width="100%">';
                        
                        // Generate potensi detail HTML
                        var potensiHtml = '<div class="col-md-12 text-center" style="margin-bottom: 20px;">' +
                            mediaHtml +
                            '</div>' +
                            '<div class="col-md-12">' +
                            '<h3>' + (potensi.nama_potensi || '') + '</h3>' +
                            '<p>' + (potensi.deskripsi || '') + '</p>' +
                            '</div>';
                        
                        // Update the container with potensi detail
                        $('#potensi-detail-container').html(potensiHtml);
                    } else {                        
                        $('#potensi-detail-container').html('<div class="col-12"><p>Potensi tidak ditemukan!</p></div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading potensi detail:', error);
                    $('#potensi-title').text('Gagal Memuat Potensi');
                    $('#potensi-detail-container').html('<div class="col-12"><p>Gagal memuat potensi. Silakan coba lagi nanti.</p></div>');
                }
            });
        }
        
        // Load potensi detail on page load
        loadPotensiDetail();
    });
</script>
@endpush
