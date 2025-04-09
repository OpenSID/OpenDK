<div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <x-button color="info" wire:click="kembali" loading="Kembali...">
                <i class="fa fa-arrow-left"></i> Kembali
            </x-button>
        </div>
        <div class="box-body">

            <form class="form-horizontal" enctype="multipart/form-data">
                <div class="box-body">

                    <x-form-group name="widget.judul">
                        <x-label for="widget.judul">Judul Widget</x-label>
                        <div class="col-sm-8">
                            <x-input type="text" placeholder="Judul Widget" wire:model="widget.judul" />
                            <x-input-validation name="widget.judul" />
                        </div>
                    </x-form-group>

                    <x-form-group name="foto">
                        <x-label for="foto">Gambar Widget</x-label>
                        <div class="col-sm-8">
                            <x-filepond
                                wire:model="foto"
                                allowImagePreview
                                imagePreviewMaxHeight="200"
                                allowFileTypeValidation
                                acceptedFileTypes="['image/png', 'image/jpg', 'image/jpeg']"
                                allowFileSizeValidation
                                maxFileSize="4mb"
                                :existingFile="$foto"
                            />
                        </div>
                    </x-form-group>

                    <x-form-group name="widget.jenis_widget">
                        <x-label for="widget.jenis_widget">Jenis Widget</x-label>
                        <div class="col-sm-8">
                            <x-tom-select wire:model="widget.jenis_widget">
                                <option value="">-- Pilih Jenis Widget --</option>
                                <option value="2">Statis</option>
                                <option value="3">Dinamis</option>
                            </x-tom-select>

                            <x-input-validation name="widget.jenis_widget" />
                        </div>
                    </x-form-group>

                    @if ($widget->jenis_widget == 2)
                        <x-form-group name="widget.isi">
                            <x-label for="widget.isi">Nama File Widget (.php)</x-label>
                            <div class="col-sm-8">

                                @if ($list_widget)
                                    <x-tom-select wire:model="widget.isi">
                                        <option value="">-- Pilih Widget --</option>
                                        @foreach ($list_widget as $list)
                                            @php
                                                $fileName = basename($list); // Ambil hanya nama file dari path
                                            @endphp
                                            <option value="{{ $fileName }}" {{ $widget['isi'] == $fileName ? 'selected' : '' }}>
                                                {{ $list }}
                                            </option>
                                        @endforeach
                                    </x-tom-select>
                                @else
                                    <span class="help-block"><code>Widget tidak tersedia atau sudah ditambahkan semua</code></span>
                                @endif

                                <x-input-validation name="widget.isi" />
                            </div>
                        </x-form-group>
                    @elseif($widget->jenis_widget == 3)
                        <x-form-group name="widget.isi">
                            <x-label for="'widget.isi">Isi</x-label>
                            <div class="col-sm-8">
                                <x-textarea wire:model="widget.isi" />

                                <x-input-validation name="'widget.isi" />
                            </div>
                        </x-form-group>

                    @endif

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
