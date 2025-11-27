<?php

namespace App\Models;

use App\Traits\HasTenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaseModel extends Model
{
    use HasTenantScope;

    public function tenant(){
        return $this->belongsTo(Tenant::class);
    }
}