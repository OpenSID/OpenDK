@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('admin-komplain.index') }}">Daftar Keluhan</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        @include('partials.flash_message')
                        <div class="float-right">
                            <div class="btn-group">
                                <a href="{{ route('admin-komplain.index') }}">
                                    <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i>
                                        Kembali
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8" style="border-right:1px solid #f3f3f3">
                                <!-- Post -->
                                <div class="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="bg-primary" style="padding: 2px;">LAPORAN:</h5>

                                            <p>Yth:
                                                {{ config('profil.sebutan_kepala_wilayah') . ' ' . config('profil.nama_kecamatan') }}
                                            </p>
                                            <br>
                                            <p>
                                                {{ $komplain->laporan }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="bg-primary" style="padding: 2px;">LAMPIRAN:</h5>
                                            @if ($komplain->lampiran1 == '' && $komplain->lampiran2 == '' && $komplain->lampiran3 == '' && $komplain->lampiran4 == '')
                                                <p>
                                                    Tidak ada lampiran.
                                                </p>
                                            @else
                                                @if (!$komplain->lampiran1 == '')
                                                    <a data-fancybox="gallery" href="{{ asset($komplain->lampiran1) }}">
                                                        <img src="{{ asset($komplain->lampiran1) }}" alt="{{ $komplain->komplain_id }}-Lampiran1" class="img-thumbnail" style="width:80px; height:100px;">
                                                    </a>
                                                @endif
                                                @if (!$komplain->lampiran2 == '')
                                                    <a data-fancybox="gallery" href="{{ asset($komplain->lampiran2) }}">
                                                        <img src="{{ asset($komplain->lampiran2) }}" alt="{{ $komplain->komplain_id }}-Lampiran2" class="img-thumbnail" style="width:80px; height:100px">
                                                    </a>
                                                @endif
                                                @if (!$komplain->lampiran3 == '')
                                                    <a data-fancybox="gallery" href="{{ asset($komplain->lampiran3) }}">
                                                        <img src="{{ asset($komplain->lampiran3) }}" alt="{{ $komplain->komplain_id }}-Lampiran3" class="img-thumbnail" style="width:80px; height:100px">
                                                    </a>
                                                @endif
                                                @if (!$komplain->lampiran4 == '')
                                                    <a data-fancybox="gallery" href="{{ asset($komplain->lampiran4) }}">
                                                        <img src="{{ asset($komplain->lampiran4) }}" alt="{{ $komplain->komplain_id }}-Lampiran4" class="img-thumbnail" style="width:80px; height:100px">
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="bg-primary" style="padding: 2px;">TINDAK LANJUT:</h5>
                                            {{ Form::hidden('komplain_id', $komplain->komplain_id, ['id' => 'komplain_id']) }}
                                            <div id="jawabans"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.post -->
                            </div>
                            <div class="col-md-4">
                                <div class="user-block">

                                    {{-- di ambil dari detail_penduduk - api database gabungan --}}
                                    @if (!empty($penduduk->detail_penduduk))
                                        <img class="img-circle img-bordered-sm" src="{{ is_user(json_decode($penduduk->detail_penduduk)->foto, json_decode($penduduk->detail_penduduk)->sex) }}" alt="user image">
                                    @else
                                        {{-- diambil dari relasi penduduk --}}
                                        <img class="img-circle img-bordered-sm" src="{{ is_user($penduduk?->penduduk?->foto, $penduduk?->penduduk?->sex) }}" alt="user image">
                                    @endif

                                    <span class="username">
                                        <a href="{{ route('sistem-komplain.komplain', $komplain->slug) }}">TRACKING ID
                                            #{{ $komplain->komplain_id }}</a>
                                    </span>
                                    <span class="description">PELAPOR : {{ $komplain->nama }}</span>
                                </div>
                                <!-- /.user-block -->
                                <br>
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th>KATEGORI</th>
                                        <td>{{ $komplain->kategori_komplain->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>TANGGAL LAPOR</th>
                                        <td>{{ format_date($komplain->created_at) }}</td>
                                    </tr>
                                    <tr>
                                        <th>STATUS</th>
                                        <td>{{ ucfirst(strtolower($komplain->status)) }}</td>
                                    </tr>
                                </table>

                                <div class="pull-right">
                                    <div class="control-group">
                                        @php $user = auth()->user(); @endphp
                                        @if (isset($user) && $user->hasRole(['super-admin', 'admin-kecamatan', 'admin-komplain']))
                                            @if ($komplain->status != 'SELESAI')
                                                <a id="btn-reply-admin" data-href="{{ route('sistem-komplain.reply', $komplain->komplain_id) }}" class="btn btn-sm btn-primary"><i class="fa fa-reply"></i> Jawab</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer with-border">
                        <div class="float-right">
                            <div class="btn-group">
                                <a href="{{ route('admin-komplain.index') }}">
                                    <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i>
                                        Kembali
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal HTML -->

    <div id="modalReplyAdmin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalAdminLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Jawab Keluhan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['id' => 'form-reply-admin', 'method' => 'POST']) !!}
                <div class="modal-body mx-3">
                    <div id="form-errors-admin"></div>
                    <div class="md-form mb-4">
                        <label>Jawaban</label>
                        {{ Form::textarea('jawaban', null, ['class' => 'form-control', 'id' => 'jawab-admin', 'required']) }}
                        {{ Form::hidden('nik', '999') }}
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <div id="modalUbahReplyAdmin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalAdminLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Ubah Jawaban Keluhan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['id' => 'form-ubah-reply-admin', 'method' => 'POST']) !!}
                <div class="modal-body mx-3">
                    <div id="form-errors-admin"></div>
                    <div class="md-form mb-4">
                        <label>Jawaban</label>
                        {{ Form::textarea('jawaban', null, ['class' => 'form-control', 'id' => 'ubah-jawab-admin', 'required']) }}
                        {{ Form::hidden('nik', '999') }}
                        {{ Form::hidden('id', null, ['id' => 'id_jawab']) }}
                        {{ Form::hidden('_method', 'PUT') }}
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

@endsection

@include('partials.asset_fancybox')
@push('scripts')
    <script type="application/javascript">


    $(function () {

        var id_komplain = $('#komplain_id').val();
        refresh_jawaban(id_komplain);

        var url = '';
        $(document).on('click', '#btn-reply-admin', function(e) {
            url = $(this).attr('data-href');
            $('#form-reply-admin').attr('action', url );
            $('#modalReplyAdmin').modal({
                backdrop: 'static',
                keyboard: false
            });
            e.preventDefault();
        });

        $('#modalReplyAdmin').on('hidden.bs.modal', function () {
            $('#jawab-admin').val('');
            $('#form-errors-admin').html('');
        })

        $('#form-reply-admin').on('submit', function(e) {
            e.preventDefault();
            var jawab = $('#jawab').val();
            var komplain_id = $('#komplain_id').val();

            $.ajax({
                type: "POST",
                url: url,
                data: $("#form-reply-admin").serialize(),
                success: function( msg ) {
                    if(msg.status == 'success'){
                        refresh_jawaban(id_komplain);
                        $('#jawab-admin').val('');
                        $('#modalReplyAdmin').modal('hide');
                    }
                },
                error :function( jqXhr ) {
                    if( jqXhr.status === 401 ) //redirect if not authenticated user.
                        $( location ).prop( 'pathname', 'auth/login' );
                    if( jqXhr.status === 422 ) {
                        //process validation errors here.
                        $errors = jqXhr.responseJSON; //this will get the errors response data.
                        console.log($errors);
                        //show them somewhere in the markup
                        //e.g
                        errorsHtml = '<div class="alert alert-danger"><ul>';

                        $.each( $errors.errors, function( key, value ) {
                            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                        });
                        errorsHtml += '</ul></di>';

                        $( '#form-errors-admin' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form
                    } else {
                        /// do some thing else
                    }
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
        });

        $(document).on('click', '#btn-ubah-reply-admin', function(e) {
            url = $(this).attr('data-href');
            $('#form-ubah-reply-admin').attr('action', url );
            
            $.ajax({
	            type: "GET",
	            url: url,
	            success: function( data ) {
	            	$('#modalUbahReplyAdmin').modal({
		                backdrop: 'static',
		                keyboard: false,
		                show: true
		            });
	            	$('#ubah-jawab-admin').val(data.data.jawaban);
	            	$('#id_jawab').val(data.data.id);
	            },
	        });
            e.preventDefault();
        });

        $('#modalUbahReplyAdmin').on('hidden.bs.modal', function () {
            $('#ubah-jawab-admin').val('');
            $('#form-errors-admin').html('');
        })

        $('#form-ubah-reply-admin').on('submit', function(e) {
            e.preventDefault();
            var jawab = $('#jawab').val();
            var komplain_id = $('#komplain_id').val();
            var id_jawab = $('#id_jawab').val();
            url = url.replace('getkomentar', 'updatekomentar');
			
            $.ajax({
                type: "POST",
                url: url,
                data: $("#form-ubah-reply-admin").serialize(),
                success: function( msg ) {
                    if(msg.status == 'success'){
                        refresh_jawaban(id_komplain);
                        $('#ubah-jawab-admin').val('');
                        $('#modalUbahReplyAdmin').modal('hide');
                    }
                },
                error :function( jqXhr ) {
                    if( jqXhr.status === 401 ) //redirect if not authenticated user.
                        $( location ).prop( 'pathname', 'auth/login' );
                    if( jqXhr.status === 422 ) {
                        //process validation errors here.
                        $errors = jqXhr.responseJSON; //this will get the errors response data.
                        console.log($errors);
                        //show them somewhere in the markup
                        //e.g
                        errorsHtml = '<div class="alert alert-danger"><ul>';

                        $.each( $errors.errors, function( key, value ) {
                            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                        });
                        errorsHtml += '</ul></di>';

                        $( '#form-errors-admin' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form
                    } else {
                        /// do some thing else
                    }
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
        });
    });

    function refresh_jawaban(id_komplain)
    {
        $.ajax({
            type: "GET",
            url: '{{ route('sistem-komplain.jawabans') }}',
            data: {id:id_komplain} ,
            success: function( data ) {
                $('#jawabans').html(data);
            },
        });
    }
</script>
@endpush
@include('partials.asset_datetimepicker')
@push('scripts')
    <script type="text/javascript">
        $(function() {

            $('.datepicker').each(function() {
                var $this = $(this);
                $this.datetimepicker({
                    format: 'YYYY-MM-DD'
                });
            });

        })
    </script>
@endpush
