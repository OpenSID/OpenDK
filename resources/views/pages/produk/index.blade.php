@extends('layouts.app   ')
@section('content')
<!-- Main content -->
<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title text-bold"><i class="fa  fa-shopping-cart fa-lg text-blue"></i> Lapak Online Kecamatan</h3>
        </div>
    </div>
  <!-- ./col -->
  @forelse ($produk as $item)
  <div class="col-md-4" style="padding-left: 0 !important;">
    <div class="box box-solid">
      <div class="box-body clearfix">
        <div class="photo" ">
          <img src="{{ $item->foto }}" class="img-responsive" alt="{{ $item->produk }}" style="height: 150px;width:100%"/>
          <div class="info">
            <div class="row">
                <div class="price col-md-10 col-sm-6">
                    <h5>{{ $item->produk}}</h5>
                    @if($item->potongan > 0)
                    <h5 class="price-text-color badge badge-danger text-danger">@currency($item->harga - $item->potongan)</h5>
                    <strike><span class="price-text-color text-danger"> @currency($item->harga)</span></strike>
                        @else
                    <h5 class="badge badge-danger">@currency($item->harga)</h5>
                    @endif
                </div>
                <div class="rating hidden-sm col-md-4  text-warning">
                    <i class="price-text-color text-warning fa fa-star"></i><i class="price-text-color fa fa-star"></i><i class="price-text-color fa fa-star"></i>
                </div>
            </div>
            <div class="separator clear-left">
                <p class="btn-add pull-right">
                    <i class="fa fa-whatsapp fa-lg text-success"></i>
                    <a target="_blank" href="https://api.whatsapp.com/send?phone=+{{ $item->kontak_pelapak }}&text=Saya Ingin Beli {{ $item->produk }}" class="hidden-sm"> <strong class="text-success">Pesan</strong></a></p>
                <p class="btn-details">
                Tersedia : {{ $item->stok }} {{ $item->satuan }}</p>
            </div>
            <div class="clearfix">
            </div>
        </div>
      </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
@empty

@endforelse
</div>

<!-- /.row -->



<!-- /.content -->
@endsection

