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

    public MediaTerkait $mediaTerkait;
    public $logo;

    public $selectedItems = [];
    public $selectAll = false; 

    public function mount(MediaTerkait $mediaTerkait)
    {
        $this->mediaTerkait = $mediaTerkait;
        $this->mediaTerkait->status = 1; // Default status to 'Tampil'
    }

    public function render()
    {
        $media_terkaits = $this->mediaTerkait->search($this->search)
                    ->status($this->status)
                    ->orderBy('urut', 'asc')
                    ->paginate($this->perPage);

        return view('livewire.informasi.media_terkait.index', [
            'media_terkaits' => $media_terkaits,
        ]);
    }

    public function rules(){

        return [
            'mediaTerkait.nama' => 'required|unique:media_terkaits,nama,'. $this->mediaTerkait->id,
            'mediaTerkait.url' => 'required',
            'mediaTerkait.status' => 'nullable|in:0,1',
            'logo' => 'nullable|mimes:jpg,png,jpeg,gif|max:1024',
        ];
    
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function lock(MediaTerkait $mediaTerkait)
    {
        $status = $mediaTerkait->status == 1 ? 'Tidak Tampil' : 'Tampil';

        $mediaTerkait->update([
            'status' => $mediaTerkait->status == 1 ? 0 : 1
        ]);

        session()->flash('success', "Data: {$mediaTerkait->judul} berhasil di {$status}");
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedItems = $this->mediaTerkait->pluck('id')->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    public function deleteSelected()
    {
        if (!empty($this->selectedItems)) {
            $this->mediaTerkait->whereIn('id', $this->selectedItems)->delete();
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
            
            if(!empty($this->logo)){
                $name_logo = time().'.'.$this->logo->guessExtension();
                $this->logo->storeAs('public/media_terkait', $name_logo);
                $this->mediaTerkait->logo = $name_logo;
            }

            $this->mediaTerkait->save();

	    	$this->clear();
            session()->flash('success', 'Data berhasil simpan!');
    	} catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
    	}
    }

    public function edit($id)
    {
    	try {
            $this->clear();
            $model = $this->mediaTerkait->findOrFail($id);
    		$this->page_description = 'Edit Media Terkait';
            $this->form = true;
            $this->editMode = true;
            $this->mediaTerkait = $model;

            $this->logo = $model->logo ? asset('storage/media_terkait/'.$model->logo) : null;
    		
    	} catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
    	}
    }

    public function update()
    {
        $this->validate();

        try {

            if($this->logo !== $this->mediaTerkait->logo)
            {
                $name_logo = time().'.'.$this->logo->guessExtension();
                $this->logo->storeAs('public/media_terkait', $name_logo);
                $this->mediaTerkait->logo = $name_logo;
            }

            $this->mediaTerkait->save();
            $this->clear();
            session()->flash('success', 'Data berhasil diperbarui!');

    	} catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
    	}
    }

    public function updateOrder($items)
    {
        foreach ($items as $index => $id) {
            $this->mediaTerkait->where('id', $id)->update(['urut' => $index + 1]);
        }

        session()->flash('success', 'Urutan berhasil diperbarui!');
    }

    public function destroy(MediaTerkait $mediaTerkait)
    {
    	try {
    		$mediaTerkait->delete();
            session()->flash('success', 'Data berhasil dihapus!');
    	}catch(\Exception $e) {
            session()->flash('error', $e->getMessage());
    	}
    }

    public function clear()
    {
        $this->resetErrorBag();
    	$this->resetExcept('mediaTerkait');
        $this->mediaTerkait = new MediaTerkait();
    }

    public function resetForm()
    {
        $this->resetErrorBag();
        $this->reset('mediaTerkait', 'logo');
        $this->mediaTerkait = new MediaTerkait();
    }
}
