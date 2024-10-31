<div>
    @if ($message)
        <div class="alert alert-{{ $alertType }}" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
            <strong>{{ $alertType }} :</strong> {{ $message }}
        </div>
    @endif
</div>
