<?php
namespace App\Models\Traits;

trait MultipleTags  {
    public function changeTags($newTagsIds, $tagsName, $sortable = false) {
        $index = 0;
        
        foreach($newTagsIds as $newTagId) {
            $this->attachTag($newTagId, $tagsName, $sortable ? $index++ : FALSE);
        }

        foreach($this->$tagsName as $tag) {
            if(!in_array($tag->id, $newTagsIds)) {
                $this->$tagsName()->detach($tag);
            }
        }
        
        $this->load($tagsName);
    }
    
    private function attachTag($newTagId, $tagsName, $index = FALSE) {
        $index === FALSE ? $this->attachNotSortableTag($newTagId, $tagsName) : $this->attachSortableTag($newTagId, $tagsName, $index);
    }
    
    private function attachNotSortableTag($newTagId, $tagsName) {
        if(!$this->$tagsName->contains($newTagId)) {
            $this->$tagsName()->attach($newTagId);
        }
    }
    
    private function attachSortableTag($newTagId, $tagsName, $index) {
        if($this->$tagsName->contains($newTagId)) {
            $this->$tagsName()->updateExistingPivot($newTagId, ['ordinal_number' => $index]);
        } else {
            $this->$tagsName()->attach([$newTagId => ['ordinal_number' => $index]]);
        }
    }
}
