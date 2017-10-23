<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Article;
use App\Tag;
use DB;

class Tag extends Model {

    use SoftDeletes;

    protected $fillable = [
        'name', 'type'
    ];

    public function articles() {
        return $this->belongsToMany(Article::class, 'article_tag', 'id_tag', 'id_article');
    }
    
    public function children() {
        return $this->belongsToMany(Tag::class, 'tag_parent', 'id_parent', 'id_tag');
    }

    public function parents() {
        return $this->belongsToMany(Tag::class, 'tag_parent', 'id_tag', 'id_parent');
    }


    public function saveTag(array $data) {
        DB::beginTransaction();
        try {
            $this->populateBasicData($data);
            $this->save();

            $this->saveParents($data);
            $this->saveChildren($data);

            DB::commit();
            return true;

        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function populateBasicData(array $data) {
        $this->name = $data['name'];
        $this->type = $data['type'];
    }

    private function saveParents(array $data) {
        $newParents = isset($data['parents']) ? $data['parents'] : array();

        $this->changeParents($newParents);

        $this->save();
    }

    private function changeParents($newTagsIds) {
        $index = 0;

        foreach($newTagsIds as $newTagId) {
            if($this->parents->contains($newTagId)) {
                $this->parents()->updateExistingPivot($newTagId, ['ordinal_number' => $index++]);
            } else {
                $this->parents()->attach([$newTagId => ['ordinal_number' => $index++]]);
            }
        }

        foreach($this->parents as $tag) {
            if(!in_array($tag->id, $newTagsIds)) {
                $this->parents()->detach($tag);
            }
        }
    }

    private function saveChildren(array $data) {
        $newChildren = isset($data['children']) ? $data['children'] : array();
        $this->changeChildren($newChildren);

        $this->save();
    }

    private function changeChildren($newTagsIds) {
        $index = 0;

        foreach($newTagsIds as $newTagId) {
            if($this->children->contains($newTagId)) {
                $this->children()->updateExistingPivot($newTagId, ['ordinal_number' => $index++]);
            } else {
                $this->children()->attach([$newTagId => ['ordinal_number' => $index++]]);
            }
        }

        foreach($this->children as $tag) {
            if(!in_array($tag->id, $newTagsIds)) {
                $this->children()->detach($tag);
            }
        }
    }
}
