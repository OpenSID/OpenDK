@extends('layouts.app   ')
@section('content')
<!-- Main content -->
<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i>   {!!  $page_description !!} {!! $desa->nama !!}</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-hover dataTable" id="datadesa-table">
                <thead>
                    <tr>
                        <th>Kode Desa</th>
                        <th>Nama Desa</th>
                        <th>Website</th>
                        <th>Luas Wilayah (km<sup>2</sup>)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{!! $desa->desa_id !!}</td>
                        <td>{!! $desa->nama !!}</td>
                        <td><a href="{!! $desa->website !!}" target="_blank">{!! $desa->website !!}</a></td>
                        <td>
                            @if ($desa->luas_wilayah)
                            {!! $desa->luas_wilayah !!} Km<sup>2</sup>
                            @endif                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /.content -->
@endsection

