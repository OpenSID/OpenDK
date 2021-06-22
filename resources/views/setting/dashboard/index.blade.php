@extends('layouts.dashboard_template')


@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard.profil')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{$page_title}}</li>
    </ol>
</section>

<section class="content container-fluid">
    @include('partials.flash_message')

    <section class="content">
        <div class="row">

            <div class="box box-primary">
                <div class="box-body">
                    @include( 'flash::message' )
                    <table class="table table-striped table-bordered" id="user-table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Halaman Judul</td>
                            <td>{{ $browser_title }}</td>
                            <td>
                                <a href="{{ route('setting.dashboard.edit_browser_title')}}" class="" title="Ubah" data-button="edit">
                                    <button type="button" class="btn btn-primary btn-xs" style="width: 40px;"><i class="fa fa-edit" aria-hidden="true"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</section>
<!-- /.content -->
@endsection

