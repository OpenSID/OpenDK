@props(['formats', 'limitKb' => 2048])
@php
    $limitEnabled = \App\Services\FileUploadService::isLimitEnabled();
    $limitMb = (int) round($limitKb / 1024);
@endphp
<small class="help-block">
    <i class="fa fa-info-circle"></i>
    Format yang diizinkan: {{ $formats }}.
    @if ($limitEnabled)
        Ukuran maksimum: <strong>{{ $limitMb }} MB</strong>.
    @endif
</small>
