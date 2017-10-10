<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Tag extends Model {

    use SoftDeletes;

    public function articles() {
    	return $this->belongsToMany('App\Article', 'article_tag', 'id_tag', 'id_article');
    }

    public function children() {
        return $this->hasMany('App\Tag', 'id_parent', 'id');
    }
    
    public function parentTag() {
        return $this->belongsTo('App\Tag', 'id_parent');
    }

// check
//    public function articles() {
//        return $this->belongsToMany(Article::class)->latest();
//    }


}
