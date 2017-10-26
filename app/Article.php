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
    
    protected $fillable = [
        'title', 'meta_title', 'meta_description', 'meta_keywords', 'content_description', 'external_url', 'status', 'id_author'
    ];

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
    
    
    public function saveArticle(array $data) {
        DB::beginTransaction();
        try {
            $this->fill($data);
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
        $content = json_decode(str_replace("'", "\"", $data['content']), true);
        
        foreach($content['data'] as $index => $elementData) {
            $this->saveElement($elementData, $index);
        }

        for($index = count($content['data']); $index < count($this->elements); $index++) {
            $this->elements()->detach($this->elements[$index]->id);
            Element::find($this->elements[$index]->id)->delete();
        }
        
        $this->load('elements');
    }
    
    private function saveElement($elementData, $index) {
        $element = count($this->elements) > $index ? $this->elements[$index] : new Element;
        $element->populateData($elementData);

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
        
        $this->load('tags');
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


    public function changeFormat($jsonEncode = true, $toHtml = false) {
        foreach($this->elements as $element) {
            $element->changeFormat($jsonEncode, $toHtml);
        }
    }
}
