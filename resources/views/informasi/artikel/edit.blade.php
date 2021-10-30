@extends('layouts.dashboard_template')

@section('content')
<section class="content-header">
    <h1>
        artikel
        <small>ubah artikel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{route('informasi.faq.index')}}">artikel</a></li>
        <li class="active">ubah artikel</li>
    </ol>
</section>

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                    {!!  Form::model($article, [ 'route' => ['informasi.artikel.update', $article->id], 'method' => 'post','id' => 'form-article', 'class' => 'form-horizontal form-label-left', 'files'=>true] ) !!}

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
    })
</script>
@endpush