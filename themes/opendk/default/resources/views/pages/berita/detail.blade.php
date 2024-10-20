@extends('layouts.app')

@push('styles')
    <style>
        .isi-artikel {
            padding: 10px;
        }

        button.btn-link {
            text-decoration: none !important;
        }

        .error-captcha {
            color: red;
            font-size: 0.875em;
        }
    </style>
@endpush

@section('content')
    <div class="col-md-8">
        <div class="post">
            <img class="img-responsive" src="{{ is_img($artikel->gambar) }}" alt="{{ $artikel->slug }}">

            <div class="isi-artikel">
                <h3 style="margin-top: 5px; text-align: justify;"><b>{{ $artikel->judul }}</b></h3>
                <p>
                    <i class="fa fa-calendar"></i>&ensp;{{ format_date($artikel->created_at) }}&ensp;|&ensp;
                    <i class="fa fa-user"></i>&ensp;Administrator
                    @if ($artikel->kategori)
                    |&ensp;<i class="fa fa-tag"></i>&ensp;<a href="{{ route('berita-kategori', ['slug' => $artikel->kategori->slug]) }}">{{ $artikel->kategori->nama_kategori }}</a>
                    @endif
                </p>
                    
                <p>{!! $artikel->isi !!}</p>
                <hr />
                <div style="margin-top:-10px" class="sharethis-inline-share-buttons"></div>
            </div>

            {{--  -----------------------------------fitur komentar start----------------------------------- --}}

            @if (session()->has('success'))
                <div id="notifikasi" class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <style>
                .sectionComment .tab-content {
                    padding: 50px 15px !important;
                    border: 1px solid #ddd;
                    background-color: white;
                    border-top: 0;
                    border-bottom-right-radius: 4px;
                    border-bottom-left-radius: 4px;
                }

                .sectionComment span.text-capitalize {
                    font-weight: 600;
                }

                .sectionComment span.fasUser {
                    display: inline-block;
                    width: 50px;
                    /* Sesuaikan ukuran sesuai kebutuhan */
                    height: 50px;
                    border-radius: 50%;
                    background-color: #E5E5E5;
                    /* Warna abu-abu */
                    text-align: center;
                    line-height: 50px;
                }

                .sectionComment span.fasUser i {
                    color: #888888;
                    /* Warna ikon putih */
                    font-size: 26px;
                }

                .media .media-object {
                    max-width: 120px;
                }

                .media-body {
                    position: relative;
                }

                .media-date {
                    position: absolute;
                    right: 25px;
                    top: 25px;
                }

                .media-date li {
                    padding: 0;
                }

                .media-date li:first-child:before {
                    content: '';
                }

                .media-date li:before {
                    content: '.';
                    margin-left: -2px;
                    margin-right: 2px;
                }

                .media-comment {
                    margin-bottom: 10px;
                }

                .media-replied {
                    margin: 0 0 0 50px;
                }

                .page-header {
                    padding-bottom: 9px;
                    margin: 40px 0 20px;
                    border-bottom: 1px solid #CCCCCC;
                }

                .btn-circle {
                    font-size: 11px;
                    padding: 6px 15px;
                    border-radius: 20px;
                    color: #fff;
                    background-color: #5bc0de;
                    border-color: #46b8da;
                }

                /* Penyesuaian untuk layar kecil (mobile) */
                @media (max-width: 767px) {
                    .media-date {
                        position: static;
                        margin-top: 5px;
                        margin-left: 0px;
                        /* Memberikan jarak di antara nama dan tanggal */
                    }
                }
            </style>

            <div class="sectionComment">

                <div class="page-header">
                    <h3 class="reviews">Tinggalkan komentar</h3>
                </div>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active">
                        <a href="#comments-logout" role="tab" data-toggle="tab">
                            <span class="reviews text-capitalize">Komentar</span>
                        </a>
                    </li>
                    <li>
                        <a href="#add-comment" role="tab" data-toggle="tab">
                            <span class="reviews text-capitalize">Kolom Komentar</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="comments-logout">

                        <ul class="media-list">
                            @forelse ($comments as $comment)
                                <li class="media">
                                    <a class="pull-left" href="javascript:void(0)">
                                        <span class="fasUser"><i class="fa fa-user"></i></span>
                                    </a>
                                    <div class="media-body">
                                        <div class="well">
                                            <h4 class="media-heading text-uppercase reviews">
                                                {{ $comment->nama }}
                                            </h4>
                                            <ul class="media-date text-uppercase reviews list-inline">
                                                <small><i>{{ $comment->created_at->locale('id')->translatedFormat('j F Y') }}</i></small>
                                            </ul>
                                            <p class="media-comment">
                                                {{ $comment->body }}
                                            </p>

                                            <button class="btn btn-info btn-circle btnModal"
                                                data-id="{{ $comment->id }}"><span
                                                    class="glyphicon glyphicon-share-alt"></span> Balas</button>
                                        </div>
                                    </div>

                                    {{-- list komentar --}}
                                    <div class="collapse in" id="replyOne" style="">
                                        <ul class="media-list">
                                            @foreach ($comment->replies as $reply)
                                                <li class="media media-replied">
                                                    <a class="pull-left" href="javascript:void(0)">
                                                        <span class="fasUser"><i class="fa fa-user"></i></span>
                                                    </a>
                                                    <div class="media-body">
                                                        <div class="well">
                                                            <h4 class="media-heading text-uppercase reviews"><span
                                                                    class="glyphicon glyphicon-share-alt"></span>
                                                                {{ $reply->nama }}
                                                            </h4>
                                                            <ul class="media-date text-uppercase reviews list-inline">
                                                                <small><i>{{ $reply->created_at->locale('id')->translatedFormat('j F Y') }}</i></small>
                                                            </ul>
                                                            <p class="media-comment">
                                                                {{ $reply->body }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @empty
                                <span>Tidak ada komentar...</span>
                            @endforelse
                        </ul>
                    </div>

                    {{-- form kirim komentar --}}
                    <div class="tab-pane" id="add-comment">
                        <form action="{{ route('comments.store') }}" method="POST" class="form-horizontal"
                            id="commentForm" role="form">
                            @csrf
                            <input type="hidden" name="comment_id" value="">
                            <input type="hidden" id="das_artikel_id" name="das_artikel_id" value="{{ $artikel->id }}">

                            <div class="form-group">
                                <label for="nama" class="col-sm-2 control-label">Nama <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        placeholder="Nama" required value="{{ old('nama') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="email" name="email"
                                        placeholder="Email" required value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="body" class="col-sm-2 control-label">Balasan <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="body" name="body" rows="3" placeholder="Tulis balasan disini.."
                                        required>{{ old('body') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label hidden-xs">&nbsp;</label>
                                <div class="col-sm-10">
                                    <div class="captcha">
                                        <span>{!! captcha_img('mini') !!}</span>
                                        <button type="button" class="btn btn-success btn-refresh" data-captcha="main"><i
                                                class="fa fa-refresh"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label hidden-xs">&nbsp;</label>
                                <div class="col-sm-10">
                                    <input id="captcha-main" type="text" class="form-control"
                                        placeholder="Masukan Kode Verifikasi" name="captcha_main" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-success" type="submit" id="submitComment">Kirim
                                        Komentar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            {{--  -----------------------------------fitur komentar end----------------------------------- --}}

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                    <h4 class="modal-title">Balas Komentar</h4>
                </div>
                <div class="modal-body">
                    {{-- <div id="placeModal"></div> --}}
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script type="text/javascript"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=61eeb3e9e14521001ac13912&product=inline-share-buttons"
        async="async"></script>
@endpush

@push('scripts')
    <script>
        $(document).on('click', '.btnModal', function(e) {
            e.preventDefault();
            // Get the comment and article IDs
            let commentId = $(this).data('id');
            let artikelId = $('#das_artikel_id').val();

            $.ajax({
                url: "{{ route('comments.modal') }}",
                type: 'GET',
                data: {
                    comment_id: commentId,
                    artikel_id: artikelId,
                },
                success: function(data) {
                    $('#exampleModal .modal-body').html(data);

                    // Show the modal
                    $('#exampleModal').modal('show');
                }
            });
        });

        // Handle CAPTCHA refresh
        $(document).on('click', '.btn-refresh', function(e) {
            e.preventDefault();
            var captchaElement = $(this).prev('span');
            $.ajax({
                type: 'GET',
                url: "{{ route('refresh-captcha') }}",
                success: function(data) {
                    captchaElement.html(data.captcha);
                }
            });
        });
    </script>
@endpush
