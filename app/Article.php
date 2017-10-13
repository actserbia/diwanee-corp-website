<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use App\Constants\ElementType;
use App\Constants\TagType;
use App\Element;
use App\Tag;
use App\User;
use DB;

class Article extends Model {

    use SoftDeletes;

    public function tags() {
        return $this->belongsToMany(Tag::class, 'article_tag', 'id_article', 'id_tag');
    }

    public function elements() {
        return $this->belongsToMany(Element::class, 'article_element', 'id_article', 'id_element');
    }
    
    public function author() {
        return $this->belongsTo(User::class, 'id_author');
    }


    public function getPublicationAttribute() {
        return $this->getSelectedTags(TagType::Publication);
    }

    public function getTypeAttribute() {
        return $this->getSelectedTags(TagType::Type);
    }

    public function getBrandAttribute() {
        return $this->getSelectedTags(TagType::Brand);
    }

    public function getCategoryAttribute() {
        return $this->getSelectedTags(TagType::Category);
    }

    public function getSubcategoriesAttribute() {
        return $this->getSelectedTags(TagType::Subcategory, false);
    }

    private function getSelectedTags($tagType, $onlyFirst = true) {
        $selectedTags = array();

        $articleTags = $this->tags->filter(function($tag) use($tagType) {
            return $tag->type === $tagType;
        });
        if(!$articleTags->isEmpty()) {
            $selectedTags = $onlyFirst ? $articleTags->first() : $articleTags;
        }

        return $selectedTags;
    }
    
    
    
    
    
    public function getContentAttribute() {
        return $this->getElementContent(ElementType::Text);
    }
    
    public function getImageAttribute() {
        return $this->getElementContent(ElementType::Image);
    }
    
    private function getElementContent($elementType) {
        $content = '';
        foreach($this->elements as $element) {
            if($element->type === $elementType) {
                $content = $element->content;
            }
        }
        return $content;
    }   
    
    
    
    public function saveArticle(array $data) {
        DB::beginTransaction();
        try {
            $this->status = $data['status'];
            if(!$this->id_author) {
                $this->id_author = $data['id_author'];
            }
            $this->title = $data['title'];
            $this->save();

            $this->saveElements($data);
            $this->saveTags($data);

            DB::commit();
            return true;

        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function saveElements(array $data) {
        $index = 0;
        if(!empty($data['image'])) {
            $this->saveElement($index, ElementType::Image, $data['image']->name);
            $index++;
        }
        
        if(!empty($data['image_old'])) {
            $this->saveElement($index, ElementType::Image, $data['image_old']);
            $index++;
        }
        
        if(!empty($data['content'])) {
            $this->saveElement($index, ElementType::Text, $data['content']);
            $index++;
        }
        
        while($index < count($this->elements)) {
            $this->elements()->detach($this->elements[$index]->id);
            Element::find($this->elements[$index]->id)->delete();
            $index++;
        }
    }
    
    private function saveElement($index, $type, $content, $options = array()) {
        $element = count($this->elements) > $index ? $this->elements[$index] : new Element;
        $element->content = $content;
        $element->options = json_encode($options);
        $element->type = $type;

        if($element->id) {
            $element->save();
        } else {
            $this->elements()->save($element, ['ordinal_number' => $index + 1]);
        }
    }


    private function saveTags(array $data) {
        $this->changeTag($this->publication, $data['publication']);
        $this->changeTag($this->type, $data['type']);
        $this->changeTag($this->brand, $data['brand']);
        $this->changeTag($this->category, $data['category']);

        $newSubcategories = isset($data['subcategories']) ? $data['subcategories'] : array();
        $this->changeTags($this->subcategories, $newSubcategories);

        $this->save();
    }

    private function changeTag($currentTag, $newTagId) {
        if(isset($currentTag->id) && $newTagId != $currentTag->id || !isset($currentTag->id) && !empty($newTagId)) {
            $this->tags()->detach($currentTag);

            if(!$this->tags->contains($newTagId)) {
                $this->tags()->attach($newTagId);
            }
        }
    }

    private function changeTags($currentTags, $newTagsIds) {
        foreach($currentTags as $tag) {
            if(!in_array($tag->id, $newTagsIds)) {
                $this->tags()->detach($tag);
            }
        }
        foreach($newTagsIds as $newTagId) {
            if(!$this->tags->contains($newTagId)) {
                $this->tags()->attach($newTagId);
            }
        }
    }
    /*private function saveTags(array $data) {
        $this->tags()->detach();
        $this->tags()->attach($data['publication']);
        $this->tags()->attach($data['type']);
        $this->tags()->attach($data['brand']);
        $this->tags()->attach($data['category']);
        if(isset($data['subcategories'])) {
            foreach($data['subcategories'] as $subcategory) {
                $this->tags()->attach($subcategory);
            }
        }
        $this->save();
    }*/
}
