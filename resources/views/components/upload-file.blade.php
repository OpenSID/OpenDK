<div x-data="{ isUploading: false, progress: 5 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false; progress = 5" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
    <input type="file" class="form-control {{ $errors->has($attributes->get('name')) ? ' is-invalid' : '' }}" wire:model.lazy="{{ $attributes->get('name') }}" id="{{ $attributes->get('name') }}{{ $attributes->get('iteration') }}">
    <div x-show.transition="isUploading" class="progress sm-progress-bar mt-2" style="height: 5px;">
        <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" x-bind:style="`width: ${progress}%`">
            <span class="sr-only" x-text="`${progress}%`"></span>
        </div>
    </div>
    @if ($errors->has($attributes->get('name')))
        <span class="help-block">
            <strong>{{ $errors->first($attributes->get('name')) }}</strong>
        </span>
    @endif
</div>
