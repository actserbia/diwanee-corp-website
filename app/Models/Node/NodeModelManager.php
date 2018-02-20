<?php
namespace App\Models\Node;

use App\Utils\Utils;
use App\Constants\Settings;
use App\Tag;
use Illuminate\Database\Eloquent\Collection;

trait NodeModelManager {
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);

        if(isset($attributes['node_type_id'])) {
            $this->populateData($attributes['node_type_id']);
        }
    }

    public static function __callStatic($method, $parameters) {
        if(in_array($method, ['findOrFail', 'find'])) {
            $object = (new static)->$method(...$parameters);
            $object->populateData();
            return $object;
        } else {
            return (new static)->$method(...$parameters);
        }
    }

    public function populateData($nodeTypeId = null) {
        $this->populateAttributesFieldsData($nodeTypeId);
        $this->populateTagFieldsData($nodeTypeId);
    }

    private function populateAttributesFieldsData($nodeTypeId = null) {
        $nodeType = isset($this->node_type) ? $this->node_type : NodeType::find($nodeTypeId);

        $this->relationsSettings['additionalData'] = [
            'relationType' => 'hasOne',
            'model' => 'App\\NodeModel\\' . ucfirst(Settings::NodeModelPrefix) . Utils::getFormattedName($nodeType->name, ' '),
            'foreignKey' => 'node_id',
            'relationKey' => 'id'
        ];
    }

    private function populateTagFieldsData($nodeTypeId = null) {
        $nodeType = isset($this->node_type) ? $this->node_type : NodeType::find($nodeTypeId);
        foreach($nodeType->tags_fields as $tagField) {
            $relationSettings = [
                'parent' => 'tags',
                'filters' => ['tag_type_id' => [$tagField->field_type_id]],
                'automaticRender' => true,
                'automaticSave' => true
            ];
            $this->relationsSettings[$tagField->formattedTitle] = $relationSettings;
            
            if($tagField->pivot->required) {
                $this->requiredFields[] = $tagField->formattedTitle;
            }
            
            $this->multipleFields[$tagField->formattedTitle] = $tagField->pivot->multiple ? [false, true, true, true, true] : [false, false, false, false, false];
        }
    }

    protected function getAutomaticRenderAtributes() {
        $fields = parent::getAutomaticRenderAtributes();

        if(isset($this->relationsSettings['additionalData'])) {
            $model = new $this->relationsSettings['additionalData']['model'];
            foreach($model->getFillableAttributes() as $field) {
                if(strpos($field, '_id') === false) {
                    $fields[] = $field;
                }
            }
        }

        return $fields;
    }

    protected function getAllAttributes() {
        if(isset($this->relationsSettings['additionalData'])) {
            $model = new $this->relationsSettings['additionalData']['model'];
            return array_merge($this->allAttributesFields, $model->getAllAttributes());
        } else {
            return $this->allAttributesFields;
        }
    }

    public function isRequired($field) {
        if(isset($this->relationsSettings['additionalData'])) {
            $model = new $this->relationsSettings['additionalData']['model'];
            $requiredFields = array_merge($this->requiredFields, $model->getRequiredAttributes());
        } else {
            $requiredFields = $this->requiredFields;
        }

        return in_array($field, $requiredFields);
    }

    public function attributeValue($field) {
        if(isset($this->additionalData)) {
            if($this->additionalData->attributeValue($field) !== null) {
                return $this->additionalData->attributeValue($field);
            }
        }

        return parent::attributeValue($field);
    }


    public function formTagsRelationValuesIdsList($relation, $level = 1) {
        $tagsIds = [];

        $selectedTags = $this->formTagsSelectedValuesByLevel($relation, $level);
        foreach($selectedTags as $tag) {
            $tagsIds[] = $tag->id;
        }

        return json_encode($tagsIds);
    }


    public function formTagsSelectedValuesByLevel($relation, $level = 1, $checkRelationItems = true) {
        if(!$checkRelationItems) {
            return [];
        }

        $relationItems = isset($this->$relation) ? $this->$relation : null;
        return $this->formTagsRelationValuesByLevel($relation, $level, null, $relationItems);
    }

    public function formTagsRelationValuesByLevel($relation, $level = 1, $tags = null, $relationItems = null) {
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