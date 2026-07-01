<?php

namespace App\Events;

use App\Models\Potensi;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PotensiChanged
{
    use Dispatchable;
    use SerializesModels;

    public $potensi;

    public function __construct(Potensi $potensi)
    {
        $this->potensi = $potensi;
    }
}
