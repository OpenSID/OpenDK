@props(['sortable' => false])

<thead>
    <tr>
        @if ($sortable)
            <th>#</th>
        @endif
        <th>
            <input type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll">
        </th>
        {{ $slot }}
    </tr>
</thead>
