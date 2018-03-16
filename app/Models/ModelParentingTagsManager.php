<?php
namespace App\Models;

use App\Tag;
use Illuminate\Database\Eloquent\Collection;
use Request;

trait ModelParentingTagsManager {
    public function formRelationValuesIdsList($relation, $level = 1) {
        $tagsIds = [];

        $selectedTags = $this->formSelectedValuesByLevel($relation, $level);
        foreach($selectedTags as $tag) {
            $tagsIds[] = $tag->id;
        }

        return json_encode($tagsIds);
    }

    public function formSelectedValuesByLevel($relation, $level = 1, $checkRelationItems = true) {
        if(!$checkRelationItems || Request::old('_token') !== null) {
            return [];
        }

        $relationItems = isset($this->$relation) ? $this->$relation : null;
        return $this->formRelationValuesByLevel($relation, $level, null, $relationItems);
    }

    public function formRelationValuesByLevel($relation, $level = 1, $tags = null, $relationItems = null) {
        if($tags !== null) {
            return $tags;
        }

        $relationsSettings = $this->getRelationSettings($relation);

        $query = Tag::select('*');
        if(isset($relationsSettings['filters'])) {
            Tag::filter($relationsSettings['filters'], $query);
        }

        $currentTags = $query->has('parents', '=', '0')->get();
        $currentRelationItems = $this->getRelationSelectedItemsWhichAreInList($currentTags, $relationItems);
        $currentLevel = 1;
        while($currentLevel < $level) {
            $currentTags = new Collection([]);
            foreach($currentRelationItems as $currentRelationItem) {
                $currentTags = $currentTags->merge($currentRelationItem->children);
            }

            $currentRelationItems = $this->getRelationSelectedItemsWhichAreInList($currentTags, $relationItems);

            $currentLevel++;
        }

        return $currentRelationItems;
    }

    private function getRelationSelectedItemsWhichAreInList($itemsList, $relationItems = null) {
        if($relationItems === null) {
            return $itemsList;
        }

        $items = [];
        foreach($relationItems as $relationItem) {
            foreach($itemsList as $item) {
                if($relationItem->id === $item->id) {
                    $items[] = $relationItem;
                }
            }
        }
        return $items;
    }
}