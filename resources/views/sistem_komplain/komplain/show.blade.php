@extends('layouts.app')

@section('content')
        <div class="col-md-8">
            <!-- kirim komplain form -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-paper-plane"></i>

                    <h3 class="box-title">{{ $page_description }}</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8" style="border-right:1px solid #f3f3f3">
                            <!-- Post -->
                            <div class="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="bg-primary" style="padding: 2px;">LAPORAN:</h5>

                                        <p>Yth: {{ config('profil.sebutan_kepala_wilayah') . ' ' . config('profil.nama_kecamatan') }}</p>
                                        <br>
                                        <p>
                                            {!! $komplain->laporan !!}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="bg-primary" style="padding: 2px;">LAMPIRAN:</h5>
                                        @if($komplain->lampiran1 == '' && $komplain->lampiran2 == '' && $komplain->lampiran3 == '' && $komplain->lampiran4 == '')
                                            <p>
                                                Tidak ada lampiran.
                                            </p>
                                        @else
                                            @if(! $komplain->lampiran1 == '')
                                                <a data-fancybox="gallery" href="{{ asset($komplain->lampiran1) }}">
                                                    <img src="{{ asset($komplain->lampiran1) }}" alt="{{ $komplain->komplain_id}}-Lampiran1" class="img-thumbnail" style="width:80px; height:100px;">
                                                </a>
                                            @endif
                                            @if(! $komplain->lampiran2 == '')
                                                <a data-fancybox="gallery" href="{{ asset($komplain->lampiran2) }}">
                                                    <img src="{{ asset($komplain->lampiran2) }}" alt="{{ $komplain->komplain_id}}-Lampiran2" class="img-thumbnail" style="width:80px; height:100px">
                                                </a>
                                            @endif
                                            @if(! $komplain->lampiran3 == '')
                                                <a data-fancybox="gallery" href="{{ asset($komplain->lampiran3) }}">
                                                    <img src="{{ asset($komplain->lampiran3) }}" alt="{{ $komplain->komplain_id}}-Lampiran3" class="img-thumbnail" style="width:80px; height:100px">
                                                </a>
                                            @endif
                                            @if(! $komplain->lampiran4 == '')
                                                <a data-fancybox="gallery" href="{{ asset($komplain->lampiran4) }}">
                                                    <img src="{{ asset($komplain->lampiran4) }}" alt="{{ $komplain->komplain_id}}-Lampiran4" class="img-thumbnail" style="width:80px; height:100px">
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
                                <img class="img-circle img-bordered-md" src="{{ asset('/bower_components/admin-lte/dist/img/user2-160x160.jpg') }}" alt="user image">
                                <span class="username">
                                    <a href="{{ route('sistem-komplain.komplain', $komplain->slug) }}">TRACKING ID #{{ $komplain->komplain_id }}</a>
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
                                    @if(isset($user) && $user->hasRole(['admin-komplain']))

                                        <a id="btn-reply-admin" data-href="{{ route('sistem-komplain.reply', $komplain->komplain_id) }}" class="btn btn-sm btn-primary"><i class="fa fa-reply"></i> Jawab</a>
                                        <a href="{{ route('sistem-komplain.edit', $komplain->komplain_id) }}"
                                            class="btn btn-sm btn-info"><i class="fa fa-edit margin-r-5"></i> Ubah</a>
                                        {!! Form::open(['method' => 'DELETE','route' => ['sistem-komplain.destroy', $komplain->id],'style' => 'display:inline']) !!}

                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin akan menghapus data tersebut?')"><i
                                                    class="fa fa-trash margin-r-5"></i> Hapus
                                        </button>

                                        {!! Form::close() !!}
                                        @else
                                        <a id="btn-reply" data-href="{{ route('sistem-komplain.reply', $komplain->komplain_id) }}" class="btn btn-sm btn-primary"><i class="fa fa-reply"></i> Jawab</a>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <a href="{{ route('sistem-komplain.index') }}" class="pull-right">
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Kembali</button>
                    </a>
                </div>
            </div>
        </div>
<!-- Modal HTML -->
<div id="modalReply" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Jawab Keluhan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['id' => 'form-reply', 'method' => 'POST']) !!}
            <div class="modal-body mx-3">
                <div id="form-errors"></div>
                <div class="md-form mb-4">
                    <label>Jawaban</label>
                    {{ csrf_field() }}
                    {{ Form::textarea('jawaban', null, ['class' => 'form-control', 'id' => 'jawab', 'required']) }}
                </div>

                <legend>Verifikasi Data Penjawab</legend>

                <div class="md-form mb-4{{ $errors->has('nik') ? ' has-error' : '' }}">
                    <label class="control-label">NIK <span class="required">*</span></label>

                    {!! Form::text('nik', null, ['placeholder' => 'NIK', 'class' => 'form-control', 'required', 'id' => 'nik']) !!}
                    @if ($errors->has('nik'))
                        <span class="help-block">
                        <strong>{{ $errors->first('nik') }}</strong>
                        </span>
                    @endif

                </div>
                <div class="md-form mb-4{{ $errors->has('tanggal_lahir') ? ' has-error' : '' }}">
                    <label class="control-label">Tanggal Lahir <span class="required">*</span></label>


                    {!! Form::text('tanggal_lahir', null, ['placeholder' => '1990-01-01', 'class' => 'form-control datepicker', 'required', 'id' => 'tanggal_lahir']) !!}
                    @if ($errors->has('tanggal_lahir'))
                        <span class="help-block">
                        <strong>{{ $errors->first('tanggal_lahir') }}</strong>
                        </span>
                    @endif

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

@endsection

@include('partials.asset_fancybox')
@push('scripts')
<script type="application/javascript">


    $(function () {

        var id_komplain = $('#komplain_id').val();
        refresh_jawaban(id_komplain);

        var url = '';
        $(document).on('click', '#btn-reply', function(e) {
            url = $(this).attr('data-href');
            $('#form-reply').attr('action', url );
            $('#modalReply').modal({
                backdrop: 'static',
                keyboard: false
            });
            e.preventDefault();
        });

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

        $('#modalReply').on('hidden.bs.modal', function () {
            $('#jawab').val('');
            $('#nik').val('');
            $('#tanggal_lahir').val('');
            $('#form-errors').html('');
        });

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

        $('#form-reply').on('submit', function(e) {
            e.preventDefault();
            var jawab = $('#jawab').val();
            var komplain_id = $('#komplain_id').val();

            $.ajax({
                type: "POST",
                url: url,
                data: $("#form-reply").serialize(),
                success: function( msg ) {
                    if(msg.status == 'success'){
                        refresh_jawaban(id_komplain);
                        $('#jawab').val('');
                        $('#modalReply').modal('hide');
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

                        $( '#form-errors' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form
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
@include(('partials.asset_datetimepicker'))
@push('scripts')

<script type="text/javascript">
    $(function () {

        $('.datepicker').each(function () {
            var $this = $(this);
            $this.datetimepicker({
                format: 'YYYY-MM-DD'
            });
        });

    })

</script>

@endpush