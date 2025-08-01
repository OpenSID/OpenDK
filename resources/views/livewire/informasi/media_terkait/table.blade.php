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
                        <option value="0">Tidak Aktif</option>
                    </x-select>
                </div>
            </div>

            <hr>

            <x-table :items="$media_terkaits">
                <x-table-columns sortable>
                    <x-table-column style="width: 5px;">No</x-table-column>
                    <x-table-column>Aksi</x-table-column>
                    <x-table-column>Nama</x-table-column>
                    <x-table-column>Logo</x-table-column>
                    <x-table-column>Status</x-table-column>
                </x-table-columns>
                <x-table-rows sortable>
                    @foreach ($media_terkaits as $item)
                        <x-table-row :id="$item->id" sortable>
                            <x-table-cell>{{ $loop->iteration }}</x-table-cell>
                            <x-table-cell>
                                <x-button-edit-table :param="$item->id" :name="$item->nama" />
                                <x-button-status-table :param="$item->id" :name="$item->nama" :icon="$item->status == 1 ? 'unlock' : 'lock'" />
                                <x-button-hapus-table :param="$item->id" :name="$item->nama" />
                            </x-table-cell>
                            <x-table-cell>{{ $item->nama }}</x-table-cell>
                            <x-table-cell>
                                <img src="{{ Storage::url('media_terkait/' . $item->logo) }}" style="width: 80px; height: 60px; object-fit: contain; background: #f5f5f5;" alt="Logo">
                            </x-table-cell>
                            <x-table-cell>
                                @if ($item->status == 1)
                                    <span class="label label-success">Aktif</span>
                                @elseif($item->status == 0)
                                    <span class="label label-danger">Tidak Aktif</span>
                                @endif
                            </x-table-cell>
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
