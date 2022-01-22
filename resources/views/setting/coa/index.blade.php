@extends('layouts.dashboard_template')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
    @include('partials.flash_message')

    <div class="box box-primary">
        <div class="box-header with-border">
            <a class="btn btn-primary btn-sm" href="{{ route('setting.coa.create') }}"><i class="fa fa-plus"></i> Tambah</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                4 - PENDAPATAN
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="box-body">
                            <table class="table table-striped table-bordered" id="data-coa">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 90px" colspan="4">Nomor Akun</th>
                                    <th>Nama Akun</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach(\App\Models\SubCoa::where('type_id', 4)->get() as $sub_coa)
                                    <tr>
                                        <td class="icon-class"></td>
                                        <td><strong>{{ 4 }}</strong></td>
                                        <td colspan="3"><strong>{{ $sub_coa->id }}</strong></td>
                                        <td><strong>&emsp;&emsp;{{ $sub_coa->sub_name }}</strong></td>
                                    </tr>
                                    @foreach(\App\Models\SubSubCoa::where('sub_id', $sub_coa->id)->get() as $sub_sub_coa)
                                        <tr>
                                            <td class="icon-class"></td>
                                            <td><strong>{{ 4 }}</strong></td>
                                            <td><strong>{{ $sub_coa->id }}</strong></td>
                                            <td colspan="2"><strong>{{ $sub_sub_coa->id }}</strong></td>
                                            <td><strong>&emsp;&emsp;&emsp;&emsp;{{ $sub_sub_coa->sub_sub_name }}</strong></td>
                                        </tr>
                                        {{-- @foreach(\App\Models\Coa::where('sub_sub_id', $sub_sub_coa->id)->get() as $coa)
                                            <tr>
                                                <td class="icon-class"></td>
                                                <td>{{ 4 }}</td>
                                                <td>{{ $sub_coa->id }}</td>
                                                <td>{{ $sub_sub_coa->id }}</td>
                                                <td>{{ $coa->id }}</td>
                                                <td>&emsp;&emsp;&emsp;&emsp;&emsp;{{ $coa->coa_name }}</td>
                                            </tr>
                                        @endforeach--}}
                                    @endforeach
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel box box-danger">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                5 - BELANJA
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="box-body">
                            <table class="table table-striped table-bordered" id="data-coa">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 90px" colspan="4">Nomor Akun</th>
                                    <th>Nama Akun</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach(\App\Models\SubCoa::where('type_id', 5)->get() as $sub_coa)
                                    <tr>
                                        <td class="icon-class"></td>
                                        <td><strong>{{ 5 }}</strong></td>
                                        <td colspan="3"><strong>{{ $sub_coa->id }}</strong></td>
                                        <td><strong>&emsp;&emsp;{{ $sub_coa->sub_name }}</strong></td>
                                    </tr>
                                    @foreach(\App\Models\SubSubCoa::where('sub_id', $sub_coa->id)->get() as $sub_sub_coa)
                                        <tr>
                                            <td class="icon-class"></td>
                                            <td><strong>{{ 5}}</strong></td>
                                            <td><strong>{{ $sub_coa->id }}</strong></td>
                                            <td colspan="2"><strong>{{ $sub_sub_coa->id }}</strong></td>
                                            <td><strong>&emsp;&emsp;&emsp;&emsp;{{ $sub_sub_coa->sub_sub_name }}</strong></td>
                                        </tr>
                                        {{--@foreach(\App\Models\Coa::where('sub_sub_id', $sub_sub_coa->id)->get() as $coa)
                                            <tr>
                                                <td class="icon-class"></td>
                                                <td>{{ $type->id }}</td>
                                                <td>{{ $sub_coa->id }}</td>
                                                <td>{{ $sub_sub_coa->id }}</td>
                                                <td>{{ $coa->id }}</td>
                                                <td>&emsp;&emsp;&emsp;{{ $coa->coa_name }}</td>
                                            </tr>
                                        @endforeach--}}
                                    @endforeach
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel box box-success">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                6 - PEMBIAYAAN
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse">
                        <div class="box-body">
                            <table class="table table-striped table-bordered" id="data-coa">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 90px" colspan="4">Nomor Akun</th>
                                    <th>Nama Akun</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach(\App\Models\SubCoa::where('type_id', 6)->get() as $sub_coa)
                                    <tr>
                                        <td class="icon-class"></td>
                                        <td><strong>{{ 6 }}</strong></td>
                                        <td colspan="3"><strong>{{ $sub_coa->id }}</strong></td>
                                        <td><strong>&emsp;&emsp;{{ $sub_coa->sub_name }}</strong></td>
                                    </tr>
                                    @foreach(\App\Models\SubSubCoa::where('sub_id', $sub_coa->id)->get() as $sub_sub_coa)
                                        <tr>
                                            <td class="icon-class"></td>
                                            <td><strong>{{ 6 }}</strong></td>
                                            <td><strong>{{ $sub_coa->id }}</strong></td>
                                            <td colspan="2"><strong>{{ $sub_sub_coa->id }}</strong></td>
                                            <td><strong>&emsp;&emsp;&emsp;&emsp;{{ $sub_sub_coa->sub_sub_name }}</strong></td>
                                        </tr>
                                        {{--@foreach(\App\Models\Coa::where('sub_sub_id', $sub_sub_coa->id)->get() as $coa)
                                            <tr>
                                                <td class="icon-class"></td>
                                                <td>{{ $type->id }}</td>
                                                <td>{{ $sub_coa->id }}</td>
                                                <td>{{ $sub_sub_coa->id }}</td>
                                                <td>{{ $coa->id }}</td>
                                                <td>&emsp;&emsp;&emsp;{{ $coa->coa_name }}</td>
                                            </tr>
                                        @endforeach--}}
                                    @endforeach
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

</section>
<!-- /.content -->
@endsection