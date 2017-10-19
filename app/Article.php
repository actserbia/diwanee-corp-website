<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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



    public function scopeWithStatus($query, $status = 1) {
        return $query->where('status', $status);
    }



    public function getPublicationAttribute() {
        return $this->getSelectedTags(TagType::Publication);
    }

    public function getInfluencerAttribute() {
        return $this->getSelectedTags(TagType::Influencer);
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
    
    
    public function getEditorContentAttribute() {
        $data = array();
        
        foreach($this->elements as $element) {
            $data[] = $element->editorContent;
        }

        $content['data'] = $data;
        
        return json_encode($content);
    }
    
    public function changeFormat($jsonEncode = true, $toHtml = false) {
        foreach($this->elements as $element) {
            $element->changeFormat($jsonEncode, $toHtml);
        }
    }
    
    
    public function saveArticle(array $data) {
        DB::beginTransaction();
        try {
            $this->populateBasicData($data);
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

    private function populateBasicData(array $data) {
        $this->status = $data['status'];
        if(!$this->id_author) {
            $this->id_author = $data['id_author'];
        }
        $this->title = $data['title'];
        $this->meta_title = $data['meta_title'];
        $this->meta_description = $data['meta_description'];
        $this->meta_keywords = $data['meta_keywords'];
        $this->content_description = $data['content_description'];
        $this->external_url = $data['external_url'];
    }

    private function saveElements(array $data) {
        $content = json_decode($data['content']);
        
        foreach($content->data as $index => $elementData) {
            $this->saveElement($elementData, $index);
        }

        for($index = count($content->data); $index < count($this->elements); $index++) {
            $this->elements()->detach($this->elements[$index]->id);
            Element::find($this->elements[$index]->id)->delete();
        }
    }
    
    private function saveElement($elementData, $index) {
        $element = count($this->elements) > $index ? $this->elements[$index] : new Element;
        $element->populateBasicData($elementData);

        if($element->id) {
            $element->save();
        } else {
            $this->elements()->save($element, ['ordinal_number' => $index + 1]);
        }
    }


    private function saveTags(array $data) {
        $this->changeTag($this->publication, $data['publication']);
        $this->changeTag($this->brand, $data['brand']);
        $this->changeTag($this->influencer, $data['influencer']);
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


    public function addSliderToElements() {
        $data = array();

        $sliderElement = new \stdClass();
        $sliderElement->type = 'slider';
        $sliderElement->images = array();
        foreach($this->elements as $element) {
            if($element->type == Constants\ElementType::SliderImage) {
                $sliderElement->images[] = $element;
            }
        }
        if(count($sliderElement->images) > 0) {
            $this->elements[] = $sliderElement;
        }

        $content['data'] = $data;

        return json_encode($content);
    }
}
