@extends('layouts.app')
@section('content')
<!-- Main content -->
<div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Profil Kependudukan</h3>
                </div>
                <!-- /.box-header -->
                @if(isset($regulasi))
                
                    <div class="box-body no-padding">

                        <table class="table table-striped">
                            <tr>
                                <th>Judul Regulasi</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                            @foreach($regulasi as $item)
                            <tr>
                                <td><a href="{{ route('unduhan.regulasi.show', ['nama_regulasi' => str_slug($item->judul)] ) }}">{{ $item->judul }}</a></td>
                            </tr>
                            @endforeach
                        </table>
                    </div>


                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        {!! $regulasi->links() !!}
                    </div>
                @else
                    <div class="box-body">
                        <h3>Data not found.</h3>
                        Sorry no data available right now!
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <div class="pull-right">

                        </div>
                    </div>
                    @endif
                            <!-- /.box-footer -->
            </div>
</div>
<!-- /.content -->
@endsection


