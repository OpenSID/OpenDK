<?php
use Carbon\Carbon;
?>
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

<!-- Main content -->
<section class="content container-fluid">
    @include('partials.flash_message')

    <section class="content">

        <!-- row -->
        <div class="row">
            <div class="col-md-8">
                <!-- The time line -->
                <ul class="timeline">

                @if(count($events) > 0)
                    @foreach($events as $key => $event)
                    <!-- timeline time label -->
                    <li class="time-label">
                        <span class="bg-red">
                            {!! Carbon::parse($key)->format('d M Y') !!}
                        </span>
                    </li>
                    <!-- /.timeline-label -->

                        @foreach($event as $value)
                                <!-- timeline item -->
                        <li>
                            <!-- timeline icon -->
                            <i class="fa fa-envelope @if($value->status=='OPEN') bg-blue @else bg-gray @endif"></i>

                            <div class="timeline-item">
                                <span class="time bg-blue label"><i class="fa fa-clock-o"></i>
                                    {{ Carbon::parse($value->start)->format('d M Y, H:m') }} s/d {{ Carbon::parse($value->end)->format('d M Y, H:m') }}</span>

                                <h3 class="timeline-header"><a href="#">{{ $value->event_name }}</a></h3>

                                <div class="timeline-body">
                                    {!! $value->description !!}
                                    @if($value->status== 'CLOSED' && !$value->attachment=="")
                                        <label class="control-label">Attachment: </label>
                                        <a href="{{ asset($value->attachment) }}" target="_blank">Download</a>
                                    @endif
                                </div>

                                <div class="timeline-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            $attendants = explode(',', trim($value->attendants));
                                            ?>
                                            <strong>Attendants:</strong>
                                            @foreach($attendants as $attendant)
                                                <span class="label label-info">{{ ucfirst($attendant) }}</span>
                                            @endforeach
                                        </div>
                                        <div class="col-md-6">
                                            <div class="pull-right">
                                                @if($value->status== 'OPEN')
                                                    @if(Sentinel::check())
                                                        <a href="{!! route('informasi.event.edit', $value->id) !!}" class="btn btn-xs btn-primary"
                                                           title="Ubah" data-button="edit"><i class="fa fa-edit"></i> Ubah
                                                        </a>

                                                        <a href="javascript:void(0)" class="" title="Hapus"
                                                           data-href="{!! route('informasi.event.destroy', $value->id) !!}" data-button="delete"
                                                           id="deleteModal">
                                                            <button type="button" class="btn btn-danger btn-xs"><i class="fa fa-trash"
                                                                                                                            aria-hidden="true"></i>
                                                                Hapus
                                                            </button>
                                                        </a>
                                                    @else
                                                        <span class="time label label-success">OPEN</span>
                                                    @endif
                                                @else
                                                    <span class="time label label-default">CLOSED</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    @endforeach
                    @else
                        <li class="time-label">
                            <span class="bg-gray">
                                Event tidak tersedia.
                            </span>
                        </li>
                @endif
                    <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                    </li>
                    <!-- END timeline item -->

                </ul>
            </div>
            <!-- /.col -->
            <div class="col-md-4">
                @if(! Sentinel::guest())
                <div class="box box-primary limit-p-width">
                    <div class="box-body">
                        <div class="caption">
                           {{-- <form class="form-horizontal">
                                <div class="input-group input-group-sm">
                                    <input class="form-control" type="text" name="cari" placeholder="Cari">
                                    <span class="input-group-btn">
                                      <button type="submit" class="btn btn-primary btn-flat">Cari</button>
                                    </span>
                                </div>
                            </form>--}}

                            <a href="{{route('informasi.event.create')}}"
                               class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Event</a>
                        </div>

                    </div>
                </div>
                @endif
            </div>
        </div>
        <!-- /.row -->

    </section>

</section>
<!-- /.content -->
@endsection

@push('scripts')

@include('forms.delete-modal')

@endpush