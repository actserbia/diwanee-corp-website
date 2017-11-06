<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Constants\TagType;
use App\Constants\ArticleStatus;
use App\Element;
use App\Tag;
use App\User;
use DB;
use App\Traits\Pagination;
use App\Traits\MultipleTags;


class Article extends Model {

    use SoftDeletes;
    use Pagination;
    use MultipleTags;
    
    protected $fillable = [
        'title', 'meta_title', 'meta_description', 'meta_keywords', 'content_description', 'external_url', 'status', 'id_author'
    ];

    protected $perPage = 12;

    public function tags() {
        return $this->belongsToMany(Tag::class, 'article_tag', 'id_article', 'id_tag');
    }

    public function elements() {
        return $this->belongsToMany(Element::class, 'article_element', 'id_article', 'id_element');
    }
    
    public function author() {
        return $this->belongsTo(User::class, 'id_author');
    }


    public function scopeWithActiveIfParamExists($query, $params) {
        if(isset($params['active'])) {
            $status = $params['active'] == 'true' ? ArticleStatus::Published : ArticleStatus::Unpublished;
            $query = $query->where('status', $status);
        }
        
        return $query;
    }
    
    public function scopeWithTagsIfParamExists($query, $params, $tagAttribute = 'name') {
        if(isset($params['tags'])) {
            foreach($params['tags'] as $tag) {
                $query = $query->whereHas('tags', function($q) use($tag, $tagAttribute) {
                    $q->where($tagAttribute, '=', $tag);
                });
            }
        }
        return $query;
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
            if(!empty($elementData['data'])) {
                $this->saveElement($elementData, $index);
            }
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
        $newTagsIds = array();
        
        foreach(TagType::getAll() as $type) {
            if(isset($data[$type]) && !empty($data[$type])) {
                $newTagsIds[] = $data[$type];
            }
        }
        if(isset($data['subcategories'])) {
            $newTagsIds = array_merge($newTagsIds, $data['subcategories']);
        }
        
        $this->changeTags($newTagsIds, 'tags');
    }


    public function changeFormat($jsonEncode = true, $toHtml = false) {
        foreach($this->elements as $element) {
            $element->changeFormat($jsonEncode, $toHtml);
        }
    }
}
