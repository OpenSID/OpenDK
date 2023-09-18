@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))

@section('custom-message')
    <p>
        Mohon Periksa Log Error dan Laporkan Masalah di
        <a href="https://github.com/OpenSID/OpenDK/issues/new?assignees=&labels=bug&template=laporan-bug.md&title=" target="_blank">Github</a>
        atau
        <a href="https://forum.opendesa.id/t/opendk" target="_blank">Forum</a>
        dengan Melampirkan Log Error
    </p>
@endsection
