@props(['items' => null])

<div class="row" style="margin-bottom: 10px;">
    <div class="col-md-12">
        <div class="pull-left">
            <label style="display: inline-block; vertical-align: middle;">Tampilkan</label>
            <x-select wire:model="perPage" style="width: 80px; display: inline-block; vertical-align: middle;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="99999">Semua</option>
            </x-select>
            <label style="display: inline-block; vertical-align: middle;">entri</label>
        </div>
        <div class="pull-right">
            <label style="display: inline-block; vertical-align: middle;">Cari:</label>
            <x-input type="search" wire:model.debounce.500ms="search" placeholder="Search..." style="width: 200px; display: inline-block; vertical-align: middle;" />
        </div>
    </div>
</div>

<div class="table-responsive">
    <table {{ $attributes }} {{ $attributes->merge(['class' => 'table table-striped table-bordered']) }}>
        {{ $slot }}
    </table>

</div>

@if ($items)
    <div class="pull-left">
        <div>
            Menampilkan {{ $items->firstItem() }} sampai {{ $items->lastItem() }} dari {{ $items->total() }} entri
        </div>
    </div>
    <div class="pull-right">
        {!! $items->links() !!}
    </div>
@endif
