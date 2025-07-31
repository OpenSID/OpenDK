<div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <x-button color="info" wire:click="kembali" loading="Kembali...">
                <i class="fa fa-arrow-left"></i> Kembali ke Daftar Media Terkait
            </x-button>
        </div>
        <div class="box-body">

            <form class="form-horizontal" enctype="multipart/form-data">
                <div class="box-body">

                    <x-form-group name="instance.nama">
                        <x-label for="instance.nama">Nama</x-label>
                        <div class="col-sm-8">
                            <x-input type="text" placeholder="Nama Media Terkait" wire:model="instance.nama" />
                            <x-input-validation name="instance.nama" />
                        </div>
                    </x-form-group>

                    <x-form-group name="instance.url">
                        <x-label for="instance.url">Link</x-label>
                        <div class="col-sm-8">
                            <x-input type="text" placeholder="Link Media Terkait" wire:model="instance.url" />
                            <x-input-validation name="instance.url" />
                        </div>
                    </x-form-group>

                    <x-form-group name="logo">
                        <x-label for="logo">Logo</x-label>
                        <div class="col-sm-8">
                            <x-filepond
                                wire:model="logo"
                                allowImagePreview
                                imagePreviewMaxHeight="200"
                                allowFileTypeValidation
                                acceptedFileTypes="['image/png', 'image/jpg', 'image/jpeg']"
                                allowFileSizeValidation
                                maxFileSize="4mb"
                                :existingFile="$logo"
                            />
                        </div>
                    </x-form-group>

                    <x-form-group name="instance.status">
                        <x-label for="instance.status">Status</x-label>
                        <div class="col-sm-8">
                            <x-tom-select wire:model="instance.status">
                                <option value="">-- Pilih Status --</option>
                                <option value="1">Tampil</option>
                                <option value="0">Tidak Tampil</option>
                            </x-tom-select>

                            <x-input-validation name="instance.status" />
                        </div>
                    </x-form-group>
                </div>
                <div class="box-footer">
                    <x-button color="danger" wire:click.prevent="resetForm" loading="Reset...">
                        <i class="fa fa-times"></i> Reset
                    </x-button>

                    <button type="button" class="btn btn-info btn-sm" wire:click.prevent="{{ $editMode ? 'update' : 'store' }}" @if (empty($logo)) disabled @endif><i class="fa fa-save"></i> {{ $editMode ? 'Update' : 'Simpan' }}</button>

                </div>
            </form>

        </div>
    </div>
</div>
