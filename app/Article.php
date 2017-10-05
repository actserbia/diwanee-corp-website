<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {
    public function tags() {
        return $this->belongsToMany('App\Tag', 'article_tag', 'id_article', 'id_tag');
    }
    
    public function elements() {
        return $this->belongsToMany('App\Element', 'article_element', 'id_article', 'id_element');
    }
    
    public function author() {
        return $this->belongsTo('App\User', 'id_author');
    }
}
