<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Article;

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
    
    public function getEditorContentAttribute() {
        $elementData = array();
        
        $elementData['type'] = $this->type;
        $elementData['data'] = json_decode($this->content);

        return $elementData;
    }
    
    public function getJsonEncodedAttribute() {
        return is_string($this->content);
    }

    public function populateBasicData($elementData) {
        $this->type = $elementData->type;
        $this->content = json_encode($elementData->data);
    }
    
    public function changeJsonEncodeFormat($encode) {
        $encode ? $this->encodeContent() : $this->decodeContent();
    }

    private function decodeContent() {
        if($this->jsonEncoded) {
            $this->content = json_decode($this->content);
        }
    }

    private function encodeContent() {
        if(!$this->jsonEncoded) {
            $this->content = json_encode($this->content);
        }
    }
}
