<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

abstract class BaseComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];    

    public string $page_title = '';
    public string $page_description = '';

    public $search = '';
    public $status = null;
    public $perPage = 10;
    public $form = false;
    public $editMode = false;

    public $selectedItems = [];
    public $selectAll = false;

    public $instance;

    protected string $fileProperty = 'logo';

    abstract public function getModelClass();

    public function mount()
    {
        $modelClass = $this->getModelClass();
        $this->instance = new $modelClass();
    }

    protected function baseRules(array $fields, array $fileFields = [])
    {
        $rules = [];
        foreach ($fields as $field => $validasi) {
            $rules["instance.$field"] = $validasi;
        }
        foreach ($fileFields as $file => $rule) {
            $rules[$file] = $rule;
        }
        return $rules;
    }

    protected function setFormState(bool $edit = false)
    {
        $this->clear();
        $this->form = true;
        $this->editMode = $edit;
        $this->page_description = ($edit ? 'Edit ' : 'Tambah ') . $this->page_title;
    }


    public function lock($id, $field = 'status', $labelField = 'judul')
    {
        $modelClass = $this->getModelClass();
        $model = $modelClass::findOrFail($id);

        $statusText = $model->$field == 1 ? 'Tidak Tampil' : 'Tampil';

        $model->update([
            $field => $model->$field == 1 ? 0 : 1
        ]);

        $label = $model->$labelField ?? 'Item';
        session()->flash('success', "Data: {$label} berhasil di {$statusText}");
    }


    public function toggleSelectAll()
    {
        $modelClass = $this->getModelClass();

        if ($this->selectAll) {
            $this->selectedItems = $modelClass::query()
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
            $this->getModelClass()::whereIn('id', $this->selectedItems)->delete();
            $this->selectedItems = [];
            $this->selectAll = false;
            session()->flash('success', 'Data berhasil dihapus!');
        }
    }

    public function updateOrder($items)
    {
        foreach ($items as $index => $id) {
            $this->getModelClass()::where('id', $id)->update(['urut' => $index + 1]);
        }

        session()->flash('success', 'Urutan berhasil diperbarui!');
    }

    public function save(array $extra = [], array $fileUploads = [])
    {
        $this->validate();

        foreach ($fileUploads as $field => $path) {
            if ($this->{$field}) {
                $filename = time() . '_' . $field . '.' . $this->{$field}->guessExtension();
                $this->{$field}->storeAs($path, $filename);
                $this->instance->{$field} = $filename;
            }
        }

        foreach ($extra as $key => $value) {
            $this->instance->{$key} = $value;
        }

        $this->instance->save();
        $this->clear();
        session()->flash('success', $this->editMode ? 'Data berhasil diperbarui!' : 'Data berhasil disimpan!');
    }


    public function editWithFiles($id, array $fileConfigs = [])
    {
        try {
            $this->clear();
            $model = $this->getModelClass()::findOrFail($id);
            $this->instance = $model;
            $this->form = true;
            $this->editMode = true;
            $this->page_description = 'Edit ' . class_basename($this->getModelClass());

            foreach ($fileConfigs as $field => $path) {
                if (property_exists($this, $field) && $model->{$field}) {
                    $this->{$field} = asset('storage/' . str_replace('public/', '', $path) . '/' . $model->{$field});
                }
            }

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }


    public function kembali()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->resetErrorBag();

        $inputsToReset = array_merge(
            ['form', 'editMode', 'instance'],
            $this->fileInputs
        );

        $this->reset(...$inputsToReset);

        $this->instance = new ($this->getModelClass());
    }


    public function destroy($id)
    {
        try {
            $model = $this->getModelClass()::findOrFail($id);
            $model->delete();

            session()->flash('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

}
