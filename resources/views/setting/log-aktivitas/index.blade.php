@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header">
        <h1>
            {{ $page_title ?? 'Riwayat Aktivitas' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title ?? 'Riwayat Aktivitas' }}</li>
        </ol>
    </section>

    <section class="content container-fluid">
        @include('partials.flash_message')

        <livewire:log-aktivitas-table />
    </section>
@endsection

@push('scripts')
    <script>
        Livewire.on('openActivityDetailModal', function () {
            var modal = document.getElementById('activityDetailModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('show');
                modal.style.display = 'block';
                document.body.classList.add('modal-open');
                var backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade in';
                document.body.appendChild(backdrop);
            }
        });

        Livewire.on('closeActivityDetailModal', function () {
            var modal = document.getElementById('activityDetailModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('show');
                modal.style.display = 'none';
                document.body.classList.remove('modal-open');
                var backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
        });
    </script>
@endpush
