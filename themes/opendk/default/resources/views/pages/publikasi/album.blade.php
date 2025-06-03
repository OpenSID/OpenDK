@extends('layouts.app')
@section('title', 'Galeri')
@section('content')
    <div class="col-md-8">
        @forelse ($albums as $item)
        <div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">
            <div class="row">
                <div class="col-sm-4">
                    <img class="img-responsive" src="{{ isThumbnail("publikasi/album/{$item->gambar}") }}" alt="{{ $item->slug }}">
                </div>
                <div class="col-sm-8">
                    <h5 style="margin-top: 5px; text-align: justify;"><b><a href="{{ route('publik.publikasi.galeri', $item->slug) }}">{{ $item->judul
                                }}</a></b></h5>
                    <p style="font-size:11px;"><i class="fa fa-calendar"></i>&ensp;{{ format_date($item->created_at)
                        }}&ensp;|&ensp;<i class="fa fa-user"></i>&ensp;Administrator</p>
                    
                    <a href="{{ route('publik.publikasi.galeri', $item->slug) }}" class="btn btn-sm btn-primary" target="_blank">Selengkapnya</a>
                </div>
            </div>
        </div>
        @empty
        <div class="callout callout-info">
            <p class="text-bold">Tidak ada Album yang ditampilkan!</p>
        </div>
        @endforelse
        <div class="text-center">
            {{ $albums->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection
