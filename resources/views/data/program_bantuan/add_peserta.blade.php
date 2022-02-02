@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('data.program-bantuan.index') }}">Program Bantuan</a></li>
        <li><a href="{{ route('data.program-bantuan.show', $program->id)}}">Program {{ $program->nama }}</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include( 'partials.flash_message' )
            
                {!! Form::open( [ 'route' => 'data.program-bantuan.add_peserta', 'method' => 'post','id' => 'form-peserta', 'class' => 'form-horizontal form-label-left'] ) !!}

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <legend>Detail Program</legend>
                            <table class="table table-bordered table-striped table-condensed">
                                <tr>
                                    <th class="col-md-2">Nama</th>
                                    <td>{{ $program->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Sasaran</th>
                                    <td>{{ $sasaran[$program->sasaran] }}</td>
                                </tr>
                                <tr>
                                    <th>Periode Program</th>
                                    <td>{{ $program->start_date }} - {{ $program->end_date }}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $program->description }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <legend>Tambahkan Peserta</legend>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Oops!</strong> Ada yang salah dengan inputan Anda.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div id="form-peserta">
                            @if($program->sasaran == 1)
                                @include('data.program_bantuan._peserta_penduduk')
                            @elseif($program->sasaran == 2)
                                @include('data.program_bantuan._peserta_kk')
                            @endif
                        </div>

                    </div>

                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <div class="control-group">
                            <a href="{{ route('data.program-bantuan.show', $program->id) }}">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Batal
                                </button>
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
@endsection
@include('partials.asset_datetimepicker')
@push('scripts')
<script>
    $(function () {


        //Datetimepicker
        $('.datepicker').each(function () {
            var $this = $(this);
            $this.datetimepicker({
                format: 'YYYY-MM-DD'
            });
        });

    })


</script>
@endpush