<?php

namespace App\Events;

use App\Models\Regulasi;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegulasiChanged
{
    use Dispatchable;
    use SerializesModels;

    public $regulasi;

    public function __construct(Regulasi $regulasi)
    {
        $this->regulasi = $regulasi;
    }
}
