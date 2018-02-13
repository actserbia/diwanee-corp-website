<?php
namespace App\Models\Node;

use App\Utils\Utils;
use Auth;

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
        $this->populateFieldsData($nodeTypeId);
        $this->populateTagFieldsData($nodeTypeId);
    }

    private function populateFieldsData($nodeTypeId = null) {
        $nodeType = isset($this->nodeType) ? $this->nodeType : NodeType::find($nodeTypeId);

        $this->relationsSettings['additionalData'] = [
            'relationType' => 'hasOne',
            'model' => 'App\\NodeModel\\' . Utils::getFormattedName($nodeType->name, ' '),
            'foreignKey' => 'node_id',
            'relationKey' => 'id'
        ];
    }

    private function populateTagFieldsData($nodeTypeId = null) {
        $nodeType = isset($this->nodeType) ? $this->nodeType : NodeType::find($nodeTypeId);
        foreach($nodeType->tags as $tagField) {
            $relationSettings = [
                'parent' => 'tags',
                'filters' => ['tag_type_id' => [$tagField->field_type_id]],
                'fillable' => true
            ];
            $this->relationsSettings[Utils::getFormattedDBName($tagField->title)] = $relationSettings;
        }
    }

    public function saveData(array $data) {
        $data['author'] = isset($this->id) ? $this->author->id : Auth::id();

        parent::saveData($data);

        $additionalData = isset($this->additionalData) ? $this->additionalData : new $this->relationsSettings['additionalData']['model'];
        $additionalData->node()->associate($this);
        $additionalData->fill($data);
        $additionalData->save();
    }

    public function getFillableFields() {
        $fields = [];

        foreach($this->fillable as $field) {
            if(strpos($field, '_id') === false) {
                $fields[] = $field;
            }
        }

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
            return array_merge($this->allFields, $model->getAllAttributes());
        } else {
            return $this->allFields;
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
}