@props(['sortable' => false])

<tr {{ $attributes }} wire:key="{{ $attributes->has('id') ? $attributes->get('id') : '' }}" id="{{ $attributes->has('id') ? $attributes->get('id') : '' }}" data-id="{{ $attributes->has('id') ? $attributes->get('id') : '' }}" class="drag-handle">
    @if ($sortable)
        <th>
            <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>
        </th>
    @endif
    <td style="width: 5px;">
        <input type="checkbox" wire:model="selectedItems" value="{{ $attributes->has('id') ? $attributes->get('id') : '' }}" />
    </td>
    {{ $slot }}
</tr>
