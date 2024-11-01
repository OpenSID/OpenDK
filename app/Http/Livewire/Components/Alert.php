<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class Alert extends Component
{
    public $message;
    public $alertType = 'success';

    protected $listeners = ['triggerAlert'];

    public function triggerAlert($message, $type = 'success')
    {
        $this->message = $message;
        $this->alertType = $type;
    }

    public function render()
    {
        return view('livewire.components.alert');
    }
}
