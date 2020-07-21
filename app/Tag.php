<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Tag::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Tag::class, 'id');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'articles_tags', 'tag_id');
    }
}
