<a href="javascript:void(0)" wire:click="$emit('destroy', {{ $attributes->get('param') }})" class="btn btn-danger btn-sm" title="Hapus: {{ $attributes->get('name') }}">
    <i class="fa fa-trash" aria-hidden="true"></i>
</a>
