@props(['sortable' => false, 'id' => null])

<tr {{ $attributes->except('id') }} @if ($id) wire:key="row-{{ $id }}" @endif
    @if ($id) id="{{ $id }}" data-id="{{ $id }}" @endif class="drag-handle">
    @if ($sortable)
        <th>
            <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>
        </th>
    @endif
    <td style="width: 5px;">
        <input type="checkbox" wire:model="selectedItems" value="{{ $id }}" />
    </td>
    {{ $slot }}
</tr>
