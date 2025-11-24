<?php

namespace App\Models;

use App\Traits\HasTenantScope;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasTenantScope;
}