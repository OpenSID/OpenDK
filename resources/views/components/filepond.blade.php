<div wire:ignore x-data x-init="() => {
    const pond = FilePond.create($refs.{{ $attributes->get('ref') ?? 'input' }});

    pond.setOptions({
        allowMultiple: {{ $attributes->has('multiple') ? 'true' : 'false' }},
        server: {
            process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress)
            },
            revert: (filename, load) => {
                @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load)
            },
        },
        allowImagePreview: {{ $attributes->has('allowFileTypeValidation') ? 'true' : 'false' }},
        imagePreviewMaxHeight: {{ $attributes->has('imagePreviewMaxHeight') ? $attributes->get('imagePreviewMaxHeight') : '256' }},
        allowFileTypeValidation: {{ $attributes->has('allowFileTypeValidation') ? 'true' : 'false' }},
        acceptedFileTypes: {!! $attributes->get('acceptedFileTypes') ?? 'null' !!},
        allowFileSizeValidation: {{ $attributes->has('allowFileSizeValidation') ? 'true' : 'false' }},
        maxFileSize: {!! $attributes->has('maxFileSize') ? "'" . $attributes->get('maxFileSize') . "'" : 'null' !!}
    });

    const existingFile = '{{ $attributes->get('existingFile') }}';
    if (existingFile && existingFile !== 'null') {
        pond.addFile(existingFile).then(file => {}).catch(error => {
            console.error('Gagal memuat file:', error);
        });
    }

}">
    <input type="file" x-ref="{{ $attributes->get('ref') ?? 'input' }}" />
</div>
@error($attributes['wire:model'])
    <p class="mt-2 text-sm text-danger">{{ $message }}</p>
@enderror
