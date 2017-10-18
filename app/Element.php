<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Article;
use App\Constants\ElementType;
use App\Constants\Settings;

class Element extends Model {
    public function articles() {
		return $this->belongsToMany(Article::class, 'article_element', 'id_element', 'id_article');
    }

    public function subelements() {
        return $this->belongsToMany(Tag::class, 'element_subelement', 'id_element', 'id_subelement');
    }

    public function parentElement() {
        return $this->belongsTo(Tag::class, 'element_subelement', 'id_subelement', 'id_element');
    }

    public function populateBasicData($elementData) {
        $this->type = $elementData->type;
        $this->content = json_encode($elementData->data);
    }
}
