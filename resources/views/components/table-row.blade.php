<tr 
{{ $attributes }}
wire:key="{{ $attributes->has('id') ? $attributes->get('id') : '' }}"
id="{{ $attributes->has('id') ? $attributes->get('id') : '' }}" 
data-id="{{ $attributes->has('id') ? $attributes->get('id') : '' }}" class="drag-handle"
>
<td style="width: 5px;">
    <input type="checkbox" wire:model="selectedItems" value="{{ $attributes->has('id') ? $attributes->get('id') : '' }}" /> 
</td>
{{ $slot }}
</tr>