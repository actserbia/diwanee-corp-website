<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Article;


class Element extends Model {
    public function articles() {
		return $this->belongsToMany(Article::class, 'article_element', 'id_element', 'id_article');
        //return $this->belongsToMany('App\Article', 'article_element', 'id_element', 'id_article');
    }
}
