<?php
namespace App\Models\Node;

use App\Utils\Utils;
use App\Constants\Settings;
use App\Tag;
use Request;

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
                'fillable' => true
            ];
            $this->relationsSettings[Utils::getFormattedDBName($tagField->title)] = $relationSettings;
            
            if($tagField->pivot->multiple) {
                $this->multipleFields[] = $tagField->formattedTitle;
            }
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

    public function formTagsRelationValues($relation, $tags = null) {
        if($tags !== null) {
            return $tags;
        }

        $relationsSettings = $this->getRelationSettings($relation);

        $query = Tag::select('*');
        if(isset($relationsSettings['filters'])) {
            Tag::filter($relationsSettings['filters'], $query);
        }

        return $query->get();
        //return $query->has('parents', '=', '0')->get();
    }

    public function formTagsSelectedValues($relation, $tags = null) {
        if(Request::old('_token') !== null) {
            $relationItems = [];
            if(Request::old($relation) !== null) {
                $relationItems = $this->getRelationModel($relation)::find(Request::old($relation));
            }
        } elseif(isset($this->$relation)) {
            $relationItems = $this->$relation;
        }

        if($tags === null) {
            return $relationItems;
        }

        $tagItems = [];
        foreach($relationItems as $relationItem) {
            foreach($tags as $tag) {
                if($relationItem->id === $tag->id) {
                    $tagItems[] = $relationItem;
                }
            }
        }
        return $tagItems;
    }
}