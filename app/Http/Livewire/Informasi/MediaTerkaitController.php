<?php

namespace App\Http\Livewire\Informasi;

use App\Http\Livewire\BaseComponent;
use App\Models\MediaTerkait;
use Livewire\WithFileUploads;

class MediaTerkaitController extends BaseComponent
{
    use WithFileUploads;

    public string $page_title = 'Media Terkait';
    public string $page_description = 'Media Terkait';

    public $logo;

    protected array $fileInputs = ['logo'];

    public function getModelClass()
    {
        return MediaTerkait::class;
    }

    public function render()
    {
        $items = $this->getModelClass()::query()
            ->search($this->search)
            ->status($this->status)
            ->orderBy('urut', 'asc')
            ->paginate($this->perPage);

        return view('livewire.informasi.media_terkait.index', ['media_terkaits' => $items]);
    }

    public function rules()
    {
        return $this->baseRules([
            'nama' => 'required|unique:media_terkaits,nama,' . $this->instance->id,
            'url' => 'required',
            'status' => 'nullable|in:0,1',
        ], [
            'logo' => 'required|mimes:jpg,png,jpeg|max:1024',
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function lock($id, $field = 'status', $labelField = 'nama')
    {
        parent::lock($id, $field, $labelField);
    }

    public function create()
    {
        $this->setFormState(false);
        $this->page_description = "Tambah Media Terkait";
    }

    public function store()
    {
        $this->save(
            ['status' => 1],
            [
                'logo' => 'public/media_terkait',
            ]
        );
    }

    public function update()
    {
        $this->save(
            [],
            [
                'logo' => 'public/media_terkait',
            ]
        );
    }

    public function edit($id)
    {
        $this->editWithFiles($id, [
            'logo' => 'public/media_terkait',
        ]);
    }



}
