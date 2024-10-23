<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'target',
        'type',
        'parent_id',
        'order',
        'is_show'
    ];

    protected $casts = [
        'is_show' => 'boolean'
    ];

    public function child(): HasMany
    {
        return $this->hasMany(NavMenu::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(NavMenu::class, 'parent_id', 'id')->with(['children' => function ($q) {
            return $q->selectRaw("id, parent_id , name as text, url as href, target, type, is_show,'fa fa-list' as icon");
        }]);
    }
}
