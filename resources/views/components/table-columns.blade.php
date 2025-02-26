<thead>
    <tr>
        <th>
            <input type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll">
        </th>
        {{ $slot }}
    </tr>
</thead>