<?php
namespace App\Models\Node;

use App\Utils\Utils;
use App\Constants\Settings;
use App\Tag;
use App\NodeType;
use Illuminate\Database\Eloquent\Collection;
use App\Constants\FieldTypeCategory;

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
        } elseif(isset(end($parameters)['node_type_id'])) {
            return (new static(array_pop($parameters)))->$method(...$parameters);
        } else {
            return (new static)->$method(...$parameters);
        }
    }

    public function populateData($nodeTypeId = null) {
        $this->nodeType = !empty($this->node_type) ? $this->node_type : NodeType::find($nodeTypeId);
        
        $this->populateAttributesFieldsData($this->xxx);
        $this->populateTagFieldsData($this->xxx);
    }

    private function populateAttributesFieldsData() {
        $this->relationsSettings['additional_data'] = [
            'relationType' => 'hasOne',
            'model' => 'App\\NodeModel\\' . ucfirst(Settings::NodeModelPrefix) . Utils::getFormattedName($this->nodeType->name, ' '),
            'foreignKey' => 'node_id',
            'relationKey' => 'id'
        ];
    }

    private function populateTagFieldsData() {
        $tagFieldsRelationName = FieldTypeCategory::Tag . '_fields';
        foreach($this->nodeType->$tagFieldsRelationName as $tagField) {
            $this->populateTagFieldData($tagField);
        }
    }
    
    private function populateTagFieldData($tagField) {
        if($tagField->pivot->active) {
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

            $this->multipleFields[$tagField->formattedTitle] = $tagField->pivot->multiple_list;
        }
    }

    protected function getAutomaticRenderAtributes() {
        $fields = parent::getAutomaticRenderAtributes();

        if(isset($this->relationsSettings['additional_data'])) {
            $model = new $this->relationsSettings['additional_data']['model'];
            foreach($model->getFillableAttributes() as $field) {
                if(strpos($field, '_id') === false) {
                    $fields[] = $field;
                }
            }
        }

        return $fields;
    }

    protected function getAllAttributes() {
        $attributes = parent::getAllAttributes();
        
        if(isset($this->relationsSettings['additional_data'])) {
            $model = new $this->relationsSettings['additional_data']['model'];
            $attributes = array_merge($attributes, $model->getAllAttributes());
        }
        
        return $attributes;
    }

    public function getRequiredAttributes() {
        $requiredFields = parent::getRequiredAttributes();
        
        if(isset($this->relationsSettings['additional_data'])) {
            $model = new $this->relationsSettings['additional_data']['model'];
            $requiredFields = array_merge($requiredFields, $model->getRequiredAttributes());
        }

        return $requiredFields;
    }
    
    public function getAllAttributesTypes() {
        $attributesTypes = parent::getAllAttributesTypes();
        
        if(isset($this->relationsSettings['additional_data'])) {
            $model = new $this->relationsSettings['additional_data']['model'];
            $attributesTypes = array_merge($attributesTypes, $model->getAllAttributesTypes());
        }
        
        return $attributesTypes;
    }

    public function attributeValue($field) {
        if(isset($this->additional_data)) {
            if($this->additional_data->attributeValue($field) !== null) {
                return $this->additional_data->attributeValue($field);
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