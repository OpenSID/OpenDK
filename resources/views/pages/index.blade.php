@extends('layouts.app')
@section('title','Beranda')
@push('css')
<style>
</style>
@endpush
@section('content')
<div class="col-md-8">
          @foreach ($artikel->channel->item as $data)
        <div class="card flex-md-row mb-4 box-shadow h-md-250">
          <div class="card-body d-flex flex-column align-items-start">
            {{-- <strong class="d-inline-block mb-2 text-primary">World</strong> --}}
            <h3 class="mb-0">
              <a class="text-dark" href="#">{{ $data->title }}</a>
            </h3>
            <div class="mb-1 text-muted">{!! $data->pubdate !!}</div>
            <p class="card-text mb-auto">{!!  $data->description  !!}.</p>
            <a href="{{ $data->link }}">Continue reading</a>
          </div>
          <img class="card-img-right flex-auto d-none d-lg-block" data-src="holder.js/200x250?theme=thumb" alt="Thumbnail [200x250]" style="width: 200px; height: 50px;" src="{{ $data->enclosure['url']}}" data-holder-rendered="true">
        </div>
        @endforeach
    </div>
@endsection


