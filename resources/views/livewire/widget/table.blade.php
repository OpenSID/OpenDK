<div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <x-button color="success" wire:click="create" loading="Tambah...">
                <i class="fa fa-plus"></i> Tambah
            </x-button>
            <button wire:click="$emit('deleteSelected')" class="btn btn-danger btn-sm" {{ count($selectedItems) === 0 ? 'disabled' : '' }}>
                Hapus Terpilih ({{ count($selectedItems) }})
            </button>
        </div>
        <div class="box-body">
            <!-- Filter dan Pencarian -->
            <div class="row">
                <div class="col-md-1">
                    <x-select wire:model="status" style="width: 80px;">
                        <option value="">Semua</option>
                        <option value="1">Aktif</option>
                        <option value="2">Tidak Aktif</option>
                    </x-select>
                </div>
            </div>

            <hr>

            <x-table :items="$widgets">
                <x-table-columns sortable>
                    <x-table-column style="width: 5px;">No</x-table-column>
                    <x-table-column>Aksi</x-table-column>
                    <x-table-column>Judul</x-table-column>
                    <x-table-column>Jenis Widget</x-table-column>
                    <x-table-column>Isi</x-table-column>
                </x-table-columns>
                <x-table-rows sortable>
                    @foreach ($widgets as $item)
                        <x-table-row :id="$item->id" sortable>
                            <x-table-cell>{{ $loop->iteration }}</x-table-cell>
                            <x-table-cell>
                                <x-button-edit-table :param="$item->id" :name="$item->judul" />
                                <x-button-status-table :param="$item->id" :name="$item->judul" :icon="$item->enabled == 1 ? 'unlock' : 'lock'" />
                                <x-button-hapus-table :param="$item->id" :name="$item->judul" />
                            </x-table-cell>
                            <x-table-cell>{{ $item->judul }}</x-table-cell>
                            <x-table-cell>
                                @if ($item->jenis_widget == 1)
                                    Sistem
                                @elseif($item->jenis_widget == 2)
                                    Statis
                                @elseif($item->jenis_widget == 3)
                                    Dinamis
                                @endif
                            </x-table-cell>
                            <x-table-cell>{{ Str::words($item->isi, 5) }}</x-table-cell>
                        </x-table-row>
                    @endforeach
                </x-table-rows>
            </x-table>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            @include('components.swal-delete')
        })
    </script>
@endpush
