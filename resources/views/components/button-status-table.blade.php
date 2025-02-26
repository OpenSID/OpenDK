<a href="javascript:void(0)" wire:click="lock({{ $attributes->get('param') }})" class="btn btn-sm" style="background-color: black;color:white;" title="{{ $attributes->get('param') == 1 ? 'Nonaktif' : 'Aktif' }}">
    <i class="fa fa-{{ $attributes->get('icon') }}" aria-hidden="true"></i>
</a>
