<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'das_articles';

    protected $fillable = ['title','slug','image','description','is_active'];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = $this->uniqueSlug($value);
    }

    private function uniqueSlug($value)
    {
        $slug = str_slug($value);
        $count = Article::where('slug', $slug)->count();
        $newCount = $count > 0 ? ++$count : '';
        return $newCount > 0 ? "$slug-$newCount" : $slug;
    }
}