<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Article;
use App\Tag;

class Tag extends Model {

    use SoftDeletes;

    public function articles() {
        return $this->belongsToMany(Article::class, 'article_tag', 'id_tag', 'id_article');
    }
    
    public function children() {
        return $this->belongsToMany(Tag::class, 'tag_parent', 'id_parent', 'id_tag');
    }

    public function parents() {
        return $this->belongsToMany(Tag::class, 'tag_parent', 'id_tag', 'id_parent');
    }
}
