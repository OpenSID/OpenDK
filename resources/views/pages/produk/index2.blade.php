@extends('layouts.app   ')
@section('content')
<div class="container" style="float: left">
    <div class="row">
        <div class="row">
            <div class="col-md-8">
                <h3>
                    Lapak Online Kecamatan</h3>
            </div>
            <div class="col-md-3">
                <!-- Controls -->
                <div class="controls pull-right ">
                    <a class="left fa fa-chevron-left btn btn-success" href="#carousel-example"
                        data-slide="prev"></a><a class="right fa fa-chevron-right btn btn-success" href="#carousel-example"
                            data-slide="next"></a>
                </div>
            </div>
        </div>
        <div id="carousel-example" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    {{-- <div class="row"> --}}
                        @forelse ($produk as $item)
                        <div class="col-sm-3">
                            <div class="col-item">
                                <div class="photo" ">
                                    <img src="{{ $item->foto }}" class="img-responsive" alt="{{ $item->produk }}" style="height: 250px;width:300px" />
                                </div>
                                <div class="info">
                                    <div class="row">
                                        <div class="price col-md-6">
                                            <h5>{{ $item->produk}}</h5>
                                            <h5 class="price-text-color">
                                                @currency($item->harga)</h5>
                                        </div>
                                        <div class="rating hidden-sm col-md-6">
                                            <i class="price-text-color fa fa-star"></i><i class="price-text-color fa fa-star">
                                            </i><i class="price-text-color fa fa-star"></i><i class="price-text-color fa fa-star">
                                            </i><i class="fa fa-star"></i>
                                        </div>
                                    </div>
                                    <div class="separator clear-left">
                                        <p class="btn-add">
                                            <i class="fa fa-whatsapp text-success"></i><a target="_blank" href="https://api.whatsapp.com/send?phone=+6281242247435&text=Saya Ingin Beli {{ $item->produk }}" class="hidden-sm"> Pesan</a></p>
                                       {{--  <p class="btn-details">
                                            <i class="fa fa-list"></i><a href="http://www.jquery2dotnet.com" class="hidden-sm">More details</a></p> --}}
                                    </div>
                                    <div class="clearfix">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                            
                        @endforelse
                    </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection