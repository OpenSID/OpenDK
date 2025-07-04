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

                    <x-form-group name="mediaTerkait.nama">
                        <x-label for="mediaTerkait.nama">Nama</x-label>
                        <div class="col-sm-8">
                            <x-input type="text" placeholder="Nama Media Terkait" wire:model="mediaTerkait.nama" />
                            <x-input-validation name="mediaTerkait.nama" />
                        </div>
                    </x-form-group>

                    <x-form-group name="mediaTerkait.url">
                        <x-label for="mediaTerkait.url">Link</x-label>
                        <div class="col-sm-8">
                            <x-input type="text" placeholder="Link Media Terkait" wire:model="mediaTerkait.url" />
                            <x-input-validation name="mediaTerkait.url" />
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

                    <x-form-group name="mediaTerkait.status">
                        <x-label for="mediaTerkait.status">Status</x-label>
                        <div class="col-sm-8">
                            <x-tom-select wire:model="mediaTerkait.status">
                                <option value="">-- Pilih Status --</option>
                                <option value="1">Tampil</option>
                                <option value="0">Tidak Tampil</option>
                            </x-tom-select>

                            <x-input-validation name="mediaTerkait.status" />
                        </div>
                    </x-form-group>
                </div>
                <div class="box-footer">
                    <x-button color="danger" wire:click.prevent="resetForm" loading="Reset...">
                        <i class="fa fa-times"></i> Reset
                    </x-button>

                    <x-button color="info" wire:click.prevent="{{ $editMode ? 'update' : 'store' }}" loading="{{ $editMode ? 'Update' : 'Simpan' }}...">
                        <i class="fa fa-save"></i> {{ $editMode ? 'Update' : 'Simpan' }}
                    </x-button>

                </div>
            </form>

        </div>
    </div>
</div>
