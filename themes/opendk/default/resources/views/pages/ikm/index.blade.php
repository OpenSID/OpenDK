@extends('layouts.app')
@section('page_title', $page_title)
@section('content')
    <div class="col-md-8">
        @include('partials.flash_message')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-bold"><i class="fa fa-arrow-circle-right fa-lg text-blue"></i> IKM
                    ({{ $page_title }})</h3>
            </div>
            <div class="box-body">
                @if (!Session::has('survey_submitted'))
                    <form action="{{ route('survei.submit') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>{{ $settings['survei_ikm'] ?? 'Menurut Anda bagaimana informasi yang tercantum dalam website ini?' }}</label>

                            @foreach (\App\Enums\SurveiEnum::getValues() as $index => $value)
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios{{ $index + 1 }}"
                                            value="{{ $value }}" required>
                                        {{ \App\Enums\SurveiEnum::getDescription($value) }}
                                    </label>
                                </div>
                            @endforeach
                            
                            @error('optionsRadios')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="consent" value="1" required>
                                Saya setuju data survei ini digunakan untuk perbaikan layanan website.
                            </label>
                            @error('consent')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Kirim Survei</button>
                        </div>
                    </form>
                @endif

                <!-- Hasil Survei -->
                <div class="mt-4">
                    <h4 class="text-center">Hasil Survei Kepuasan</h4>
                    @if (!empty($results))
                        <div class="form-group">
                            <label>{{ $settings['survei_ikm'] ?? 'Menurut Anda bagaimana informasi yang tercantum dalam website ini?' }}</label>
                            <ul>
                                @foreach (\App\Enums\SurveiEnum::getValues() as $value)
                                    <li>{{ \App\Enums\SurveiEnum::getDescription($value) }} ({{ $results[\App\Enums\SurveiEnum::getDescription($value)] ?? 0 }})</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="text-center">Belum ada data survei.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <style>
        ol,
        ul {
            margin-top: 0;
            padding-left: 15px !important;
            margin-bottom: 10px;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">
        $(function() {
            setTimeout(function() {
                $("#notifikasi").slideUp("slow");
            }, 2000);
        });
    </script>
@endpush
