<?php

namespace App\Http\Livewire\Widget;

use App\Models\Widget;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Artisan;

class WidgetController extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];    

    public string $page_title = 'Widget';
    public string $page_description = 'Daftar Widget';

    public $page = 1;
    public $perPage = 10;
    public $search;
    public $status;
    public $form = false;
    public $editMode = false;
    
    public Widget $widget;
    public $foto;

    public $selectedItems = [];
    public $selectAll = false; 
    public $list_widget;

    public function mount(Widget $widget)
    {
        $this->widget = $widget;
        $this->updateListWidget();

        Artisan::call('widgets:sync');
    }

    public function updateListWidget()
    {
        $existingWidgets = Widget::pluck('isi')->map(function ($isi) {
            return str_replace('.blade.php', '', basename($isi));
        })->toArray();

        $currentWidgetName = $this->editMode && $this->widget && $this->widget->isi
            ? str_replace('.blade.php', '', basename($this->widget->isi))
            : null;

        $list_widget = Widget::listWidgetBaru();

        $this->list_widget = array_filter($list_widget, function ($path) use ($existingWidgets, $currentWidgetName) {
            $fileName = str_replace('.blade.php', '', basename($path));
            return !in_array($fileName, $existingWidgets) || $fileName === $currentWidgetName;
        });
    }

    public function render()
    {
        $widgets = Widget::search($this->search)
                    ->orderBy('urut', 'asc')
                    ->statusAdmin($this->status)
                    ->paginate($this->perPage);
                    
        return view('livewire.widget.index', [
            'widgets' => $widgets
        ]);
    }

    public function rules(){

        return [
            'widget.judul' => 'required|unique:widgets,judul,'. $this->widget->id,
            'widget.isi' => 'required',
            'widget.form_admin' => 'nullable',
            'widget.jenis_widget' => 'required',
            'foto' => 'nullable|mimes:jpg,png,jpeg,gif|max:1024',
        ];
    
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function lock(Widget $widget)
    {
        $status = $widget->enabled == 1 ? 'Nonaktif' : 'Aktif';

        $widget->update([
            'enabled' => $widget->enabled == 1 ? 2 : 1
        ]);

        session()->flash('success', "Data: {$widget->judul} berhasil di {$status}");
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedItems = Widget::pluck('id')->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    public function deleteSelected()
    {
        if (!empty($this->selectedItems)) {
            Widget::whereIn('id', $this->selectedItems)->delete();
            $this->selectedItems = [];
            $this->selectAll = false;
            session()->flash('success', 'Data berhasil dihapus!');
        }
    }

    public function create()
    {
        $this->clear();
        $this->page_description = "Tambah Widget";
        $this->form = true;
        $this->editMode = false;
        $this->updateListWidget();
    }

    public function kembali()
    {
        $this->clear();
    }

    public function store()
    {
        $this->validate();

        try {
            $this->cek_tidy();

            if(!empty($this->foto)){
                $name_foto = time().'.'.$this->foto->guessExtension();
                $this->foto->storeAs('public/widget', $name_foto);
                $this->widget->foto = $name_foto;
            }

            $this->widget->enabled = 1;
            $this->widget->isi = $this->widget->jenis_widget == 2 ? basename(bersihkan_xss($this->widget->isi)) : $this->bersihkan_html($this->widget->isi);

            $this->widget->save();
	    	$this->clear();
            session()->flash('success', 'Data berhasil simpan!');
    	} catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
    	}
    }

    private function bersihkan_html($isi): string
    {
        // Konfigurasi tidy
        $config = [
            'indent'              => true,
            'output-xhtml'        => true,
            'show-body-only'      => true,
            'clean'               => true,
            'coerce-endtags'      => true,
            'drop-empty-elements' => false,
            'preserve-entities'   => true,
        ];

        $tidy = new \tidy();
        $tidy->parseString($isi, $config, 'utf8');
        $tidy->cleanRepair();

        return tidy_get_output($tidy);
    }

    private function cek_tidy(): void
    {
        if (! in_array('tidy', get_loaded_extensions())) {
            $pesan = 'Ektensi Tidy tidak aktif';

            session()->flash('error', $pesan);
        }
    }

    public function edit($id)
    {
    	try {
            $this->clear();
            $model = $this->widget->findOrFail($id);
    		$this->page_description = 'Edit Widget';
            $this->form = true;
            $this->editMode = true;
            $this->widget = $model;
            $this->updateListWidget();

            $this->foto = $model->foto ? asset('storage/widget/'.$model->foto) : null;
    		
    	} catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
    	}
    }

    public function update()
    {
        $this->validate();

        try {

            $this->cek_tidy();

            if($this->foto !== $this->widget->foto)
            {
                $name_foto = time().'.'.$this->foto->guessExtension();
                $this->foto->storeAs('public/widget', $name_foto);
                $this->widget->foto = $name_foto;
            }

            $this->widget->isi = $this->widget->jenis_widget == 2 ? basename(bersihkan_xss($this->widget->isi)) : $this->bersihkan_html($this->widget->isi);
            $this->widget->save();
            $this->clear();
            session()->flash('success', 'Data berhasil diperbarui!');

    	} catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
    	}
    }

    public function updateOrder($items)
    {
        foreach ($items as $index => $id) {
            Widget::where('id', $id)->update(['urut' => $index + 1]);
        }

        session()->flash('success', 'Urutan berhasil diperbarui!');
    }

    public function destroy(Widget $widget)
    {
    	try {
    		$widget->delete();
            session()->flash('success', 'Data berhasil dihapus!');
    	}catch(\Exception $e) {
            session()->flash('error', $e->getMessage());
    	}
    }

    public function clear()
    {
        $this->resetErrorBag();
    	$this->resetExcept('widget', 'list_widget');
        $this->widget = new Widget();
    }

    public function resetForm()
    {
        $this->resetErrorBag();
        $this->reset('widget', 'foto');
        $this->widget = new Widget();
    }
}
