<tr 
{{ $attributes }}
>
<td style="width: 5px;">
    <input type="checkbox" wire:model="selectedItems" value="{{ $attributes->has('id') ? $attributes->get('id') : '' }}">
</td>
{{ $slot }}
</tr>