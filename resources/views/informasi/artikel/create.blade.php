@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        Artikel
        <small>Tambah artikel baru</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{route('informasi.artikel.index')}}">artikel</a></li>
        <li class="active">tambah artikel baru</li>
    </ol>
</section>

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
            
                {!! Form::open(['url'=>route('informasi.artikel.store'),'class'=>'form-horizontal','files'=>true])!!}

                <div class="box-body">
                    @include( 'flash::message' )
                    @include('informasi.artikel._form')
                </div>
                
                {!! Form::close() !!}
            </div>
        </div>
    </div>

</section>
@endsection

@include(('partials.asset_wysihtml5'))
@push('scripts')
<script>
    $(function () {
        $('.textarea').wysihtml5()
    });
</script>
@endpush