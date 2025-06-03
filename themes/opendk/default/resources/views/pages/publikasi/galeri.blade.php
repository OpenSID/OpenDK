@extends('layouts.app')
@section('title', 'Galeri')
@section('content')
    <div class="col-md-8">
        @forelse ($galeris as $item)

        @if ($item->jenis == 'file')
            
        
        <div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">
            <div class="row">
                <div class="col-sm-4">
                    <img class="img-responsive" src="{{ isThumbnail("publikasi/galeri/{$item->gambar[0]}") }}" alt="{{ $item->slug }}">
                </div>
                <div class="col-sm-8">
                    <h5 style="margin-top: 5px; text-align: justify;"><b><a href="{{ route('publik.publikasi.galeri.detail', $item->slug) }}">{{ $item->judul
                                }}</a></b></h5>
                    <p style="font-size:11px;"><i class="fa fa-calendar"></i>&ensp;{{ format_date($item->created_at)
                        }}&ensp;|&ensp;<i class="fa fa-user"></i>&ensp;Administrator</p>
                    
                    <a href="{{ route('publik.publikasi.galeri.detail', $item->slug) }}" class="btn btn-sm btn-primary" target="_blank">Selengkapnya</a>
                </div>
            </div>
        </div>

        @else
        <div class="post" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;">
            <div class="row">
                <div class="col-sm-4">
                    <img class="img-responsive" src="{{ asset('/img/no-image.png') }}" alt="{{ $item->slug }}">
                </div>
                <div class="col-sm-8">
                    <h5 style="margin-top: 5px; text-align: justify;"><b><a
                                href="{{ $item->link }}">{{ $item->judul
                                }}</a></b></h5>
                    <p style="font-size:11px;"><i class="fa fa-calendar"></i>&ensp;{{ format_date($item->created_at)
                        }}&ensp;|&ensp;<i class="fa fa-user"></i>&ensp;Administrator</p>
        
                    <a href="{{ $item->link }}" class="btn btn-sm btn-primary"
                        target="_blank">Selengkapnya</a>
                </div>
            </div>
        </div>
        @endif
        @empty
        <div class="callout callout-info">
            <p class="text-bold">Tidak ada galeri yang ditampilkan!</p>
        </div>
        @endforelse
        <div class="text-center">
            {{ $galeris->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection
