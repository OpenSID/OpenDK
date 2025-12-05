<?php

namespace App\Http\Livewire\Informasi;

use App\Models\MediaTerkait;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MediaTerkaitController extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public string $page_title = 'Media Terkait';
    public string $page_description = 'Media Terkait';

    public $page = 1;
    public $perPage = 10;
    public $search;
    public $status;
    public $form = false;
    public $editMode = false;

    // Media Terkait properties untuk Livewire 3 compatibility
    public $media_terkait_id = null;
    public $media_terkait = [
        'nama' => '',
        'url' => '',
        'status' => 1,
    ];
    public $logo;

    public $selectedItems = [];
    public $selectAll = false;

    public function render()
    {
        $items = MediaTerkait::query()
            ->search($this->search)
            ->status($this->status)
            ->orderBy('urut', 'asc')
            ->paginate($this->perPage);

        return view('livewire.informasi.media_terkait.index', ['media_terkaits' => $items]);
    }

    public function rules()
    {
        return [
            'media_terkait.nama' => 'required|unique:media_terkaits,nama,' . ($this->media_terkait_id ?? 'NULL'),
            'media_terkait.url' => 'required',
            'media_terkait.status' => 'nullable|in:0,1',
            'logo' => $this->editMode ? 'nullable|mimes:jpg,png,jpeg|max:1024' : 'required|mimes:jpg,png,jpeg|max:1024',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function lock($id)
    {
        $model = MediaTerkait::findOrFail($id);
        $statusText = $model->status == 1 ? 'Tidak Tampil' : 'Tampil';

        $model->update([
            'status' => $model->status == 1 ? 0 : 1
        ]);

        session()->flash('success', "Data: {$model->nama} berhasil di {$statusText}");
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedItems = MediaTerkait::query()
                ->when($this->search, fn ($q) => $q->search($this->search))
                ->when(is_numeric($this->status), fn ($q) => $q->where('status', $this->status))
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    public function deleteSelected()
    {
        if (!empty($this->selectedItems)) {
            MediaTerkait::whereIn('id', $this->selectedItems)->delete();
            $this->selectedItems = [];
            $this->selectAll = false;
            session()->flash('success', 'Data berhasil dihapus!');
        }
    }

    public function create()
    {
        $this->clear();
        $this->page_description = "Tambah Media Terkait";
        $this->form = true;
        $this->editMode = false;
    }

    public function kembali()
    {
        $this->clear();
    }

    public function store()
    {
        $this->validate();

        try {
            $model = new MediaTerkait();
            $model->nama = $this->media_terkait['nama'];
            $model->url = $this->media_terkait['url'];
            $model->status = 1;

            if (!empty($this->logo)) {
                $name_logo = time() . '.' . $this->logo->guessExtension();
                $this->logo->storeAs('public/media_terkait', $name_logo);
                $model->logo = $name_logo;
            }

            $model->save();
            $this->clear();
            session()->flash('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $this->clear();
            $model = MediaTerkait::findOrFail($id);
            $this->page_description = 'Edit Media Terkait';
            $this->form = true;
            $this->editMode = true;
            $this->media_terkait_id = $model->id;
            $this->media_terkait = [
                'nama' => $model->nama,
                'url' => $model->url,
                'status' => $model->status,
            ];

            $this->logo = $model->logo ? asset('storage/media_terkait/' . $model->logo) : null;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function update()
    {
        if (!$this->media_terkait_id) {
            session()->flash('error', 'ID Media Terkait tidak ditemukan. Silakan coba lagi.');
            return;
        }

        $this->validate();

        try {
            $model = MediaTerkait::findOrFail($this->media_terkait_id);
            $model->nama = $this->media_terkait['nama'];
            $model->url = $this->media_terkait['url'];
            $model->status = $this->media_terkait['status'] ?? $model->status;

            if ($this->logo && !is_string($this->logo)) {
                $name_logo = time() . '.' . $this->logo->guessExtension();
                $this->logo->storeAs('public/media_terkait', $name_logo);
                $model->logo = $name_logo;
            }

            $model->save();
            $this->clear();
            session()->flash('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function updateOrder($items)
    {
        foreach ($items as $index => $id) {
            MediaTerkait::where('id', $id)->update(['urut' => $index + 1]);
        }

        session()->flash('success', 'Urutan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            $model = MediaTerkait::findOrFail($id);
            $model->delete();
            session()->flash('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function clear()
    {
        $this->resetErrorBag();
        $this->media_terkait_id = null;
        $this->media_terkait = [
            'nama' => '',
            'url' => '',
            'status' => 1,
        ];
        $this->logo = null;
        $this->form = false;
        $this->editMode = false;
        $this->page_description = 'Media Terkait';
    }

    public function resetForm()
    {
        $this->resetErrorBag();
        $this->media_terkait = [
            'nama' => '',
            'url' => '',
            'status' => 1,
        ];
        $this->logo = null;
    }
}
