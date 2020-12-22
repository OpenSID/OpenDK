@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? "Page Title" }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{!! $page_title !!}</li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            @forelse ($feeds as $item)
                            <div class="col-md-3">
                                <div class="thumbnail">
                                    <a href="{{ $item['link'] }}" target="_blank">
                                        <img src="{{ get_tag_image($item['description']) }}" alt="Lights" style="height: 100%; width: 100%; display: block;">
                                        <div class="caption">
                                            <h4><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></h4>
                                            <h6>{{ $item['feed_title'] }} | {{ $item['author']}}</h6>
                                            <p>{{ strip_tags($item['description']) }}</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            @empty
                                
                            @endforelse
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        {{ $feeds->links() }}
                    </div>
                  </div>
            </div>
        </div>
    </section>
@endsection