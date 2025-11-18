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
    
    // Widget properties untuk Livewire 3 compatibility
    public $widget_id = null;
    public $widget = [
        'judul' => '',
        'isi' => '',
        'form_admin' => '',
        'jenis_widget' => '',
        'foto' => '',
        'enabled' => 1
    ];
    public $foto;

    public $selectedItems = [];
    public $selectAll = false; 
    public $list_widget;

    public function mount()
    {
        $this->updateListWidget();
        Artisan::call('widgets:sync');
    }

    public function updateListWidget()
    {
        $existingWidgets = Widget::pluck('isi')->map(function ($isi) {
            return str_replace('.blade.php', '', basename($isi));
        })->toArray();

        $currentWidgetName = $this->editMode && isset($this->widget['isi']) && $this->widget['isi']
            ? str_replace('.blade.php', '', basename($this->widget['isi']))
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
            'widget.judul' => 'required|unique:widgets,judul,'. ($this->widget_id ?? 'NULL'),
            'widget.isi' => 'required',
            'widget.form_admin' => 'nullable',
            'widget.jenis_widget' => 'required',
            'foto' => 'nullable|mimes:jpg,png,jpeg,gif|max:1024',
        ];
    
    }

    public function updated($propertyName)
    {
        // Skip validation untuk jenis_widget agar tidak error saat switch
        if ($propertyName === 'widget.jenis_widget') {
            $this->updateListWidget();
            return;
        }
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

            $widgetModel = new Widget();
            $widgetModel->judul = $this->widget['judul'];
            $widgetModel->form_admin = $this->widget['form_admin'] ?? null;
            $widgetModel->jenis_widget = $this->widget['jenis_widget'];
            $widgetModel->enabled = 1;

            if(!empty($this->foto)){
                $name_foto = time().'.'.$this->foto->guessExtension();
                $this->foto->storeAs('public/widget', $name_foto);
                $widgetModel->foto = $name_foto;
            }

            $widgetModel->isi = $this->widget['jenis_widget'] == 2 
                ? basename(bersihkan_xss($this->widget['isi'])) 
                : $this->bersihkan_html($this->widget['isi']);

            $widgetModel->save();
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
            $model = Widget::findOrFail($id);
    		$this->page_description = 'Edit Widget';
            $this->form = true;
            $this->editMode = true;
            $this->widget_id = $model->id;
            $this->widget = [
                'judul' => $model->judul,
                'isi' => $model->isi,
                'form_admin' => $model->form_admin,
                'jenis_widget' => $model->jenis_widget,
                'foto' => $model->foto,
                'enabled' => $model->enabled
            ];
            $this->updateListWidget();

            $this->foto = $model->foto ? asset('storage/widget/'.$model->foto) : null;
    		
    	} catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
    	}
    }

    public function update()
    {
        // Pastikan widget_id ada untuk update
        if (!$this->widget_id) {
            session()->flash('error', 'ID Widget tidak ditemukan. Silakan coba lagi.');
            return;
        }

        $this->validate();

        try {
            $this->cek_tidy();

            $widgetModel = Widget::findOrFail($this->widget_id);
            $widgetModel->judul = $this->widget['judul'];
            $widgetModel->form_admin = $this->widget['form_admin'] ?? null;
            $widgetModel->jenis_widget = $this->widget['jenis_widget'];

            if($this->foto && !is_string($this->foto))
            {
                $name_foto = time().'.'.$this->foto->guessExtension();
                $this->foto->storeAs('public/widget', $name_foto);
                $widgetModel->foto = $name_foto;
            }

            $widgetModel->isi = $this->widget['jenis_widget'] == 2 
                ? basename(bersihkan_xss($this->widget['isi'])) 
                : $this->bersihkan_html($this->widget['isi']);
            
            $widgetModel->save();
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
    	$this->resetExcept('list_widget');
        $this->widget_id = null;
        $this->widget = [
            'judul' => '',
            'isi' => '',
            'form_admin' => '',
            'jenis_widget' => '',
            'foto' => '',
            'enabled' => 1
        ];
        $this->foto = null;
        $this->form = false;
        $this->editMode = false;
        $this->page_description = 'Daftar Widget';
    }

    public function resetForm()
    {
        $this->resetErrorBag();
        $this->widget = [
            'judul' => '',
            'isi' => '',
            'form_admin' => '',
            'jenis_widget' => '',
            'foto' => '',
            'enabled' => 1
        ];
        $this->foto = null;
    }
}
