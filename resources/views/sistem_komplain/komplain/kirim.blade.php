@extends('layouts.app')

@section('content')
<!-- Main content -->
        <!-- /.col -->
        <div class="col-md-8">
            <!-- kirim komplain form -->
            {!! Form::open( [ 'route' => 'sistem-komplain.store', 'method' => 'post','id' => 'form-komplain', 'class' => 'form-horizontal form-label-left', 'files'=>true] ) !!}
            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-paper-plane"></i>

                    <h3 class="box-title">{{ $page_title }}</h3>
                </div>
                <div class="box-body">
                    @include('partials.flash_message')
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Ups!</strong> Ada beberapa masalah dengan masukan Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @include( 'flash::message' )

                    <div class="row">
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="form-group{{ $errors->has('kategori') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">Kategori <span class="required">*</span></label>

                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    {!! Form::select('kategori', \App\Models\KategoriKomplain::pluck('nama', 'id'), null, ['class' => 'form-control', 'id' => 'kategori', 'required']) !!}
                                    @if ($errors->has('kategori'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('kategori') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('judul') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">Judul <span class="required">*</span></label>

                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    {!! Form::text('judul', null, ['placeholder' => 'Judul', 'class' => 'form-control', 'required']) !!}
                                    @if ($errors->has('judul'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('judul') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('laporan') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">Laporan <span class="required">*</span></label>

                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    {!! Form::textArea('laporan', null, ['placeholder' => 'Laporan', 'class' => 'form-control', 'required']) !!}
                                    @if ($errors->has('laporan'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('laporan') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('laporan1') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">Lampiran</label>

                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="lampiran1" name="lampiran1" accept="image/*" />
                                            <label for="lampiran1"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="lampiranPreview1" style="background-image: url(https://via.placeholder.com/80x100);">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="lampiran2" name="lampiran2" accept="image/*" />
                                            <label for="lampiran2"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="lampiranPreview2" style="background-image: url(https://via.placeholder.com/80x100);">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="lampiran3" name="lampiran3" accept="image/*" />
                                            <label for="lampiran3"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="lampiranPreview3" style="background-image: url(https://via.placeholder.com/80x100);">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="lampiran4" name="lampiran3" accept="image/*" />
                                            <label for="lampiran4"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="lampiranPreview4" style="background-image: url(https://via.placeholder.com/80x100);">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <legend>Verifikasi Data Pelapor</legend>

                            <div class="form-group{{ $errors->has('nik') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">NIK <span class="required">*</span></label>

                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    {!! Form::text('nik', null, ['placeholder' => 'NIK', 'class' => 'form-control', 'required']) !!}
                                    @if ($errors->has('nik'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nik') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('tanggal_lahir') ? ' has-error' : '' }}">
                                <label class="control-label col-md-2 col-sm-3 col-xs-12">Tanggal Lahir <span class="required">*</span></label>

                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    {!! Form::text('tanggal_lahir', null, ['placeholder' => '1990-01-01', 'class' => 'form-control datepicker', 'required' ]) !!}
                                    @if ($errors->has('tanggal_lahir'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tanggal_lahir') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
                                <label for="password" class="control-label col-md-2 col-sm-3 col-xs-12">Kode Verifikasi <span class="required">*</span></label>

                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <div class="captcha">
                                        <span>{!! captcha_img('mini') !!}</span>
                                        <button type="button" class="btn btn-success btn-refresh"><i class="fa fa-refresh"></i></button>
                                    </div>
                                    <input id="captcha" type="text" class="form-control" placeholder="Masukan Kode Verifikasi" name="captcha">
                                    @if ($errors->has('captcha'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('captcha') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="box-footer clearfix">
                    <div class="pull-right">
                        <div class="control-group">
                            <a href="{{ route('sistem-komplain.index') }}">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Batal
                                </button>
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-paper-plane"></i> Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
<!-- /.content -->
        </div>
@endsection

@include('partials.asset_upload_images')
@include(('partials.asset_datetimepicker'))

@push('scripts')
<script type="text/javascript">
    $(function () {
        $(".btn-refresh").click(function(){
            $.ajax({
                type:'GET',
                url:'/refresh-captcha',
                success:function(data){
                    $(".captcha span").html(data.captcha);
                }
            });
        });

        $('.datepicker').each(function () {
            var $this = $(this);
            $this.datetimepicker({
                format: 'YYYY-MM-DD'
            });
        });

        setTimeout(function() {
            $("#notifikasi").slideUp("slow");
        }, 2000);

    })

</script>
@endpush