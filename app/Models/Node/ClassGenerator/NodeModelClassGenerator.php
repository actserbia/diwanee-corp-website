<?php

namespace App\Models\Node\ClassGenerator;

use App\Constants\AttributeFieldType;
use App\Constants\FieldTypeCategory;

class NodeModelClassGenerator extends ClassGenerator {
    const FOLDER = 'NodeModel';

    protected $fillable = [];
    protected $allAttributesFields = ['id'];
    protected $requiredFields = [];
    protected $filterFields = [];
    protected $attributeType = [];
    protected $defaultFieldsValues = [];

    protected $relationsSettings = [
        'node' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\Node',
            'foreignKey' => 'node_id'
        ]
    ];

    protected $multipleFields = [];

    protected function populateData() {
        $attributeFieldsRelationName = FieldTypeCategory::Attribute . '_fields';
        foreach($this->model->$attributeFieldsRelationName as $field) {
            if($field->pivot->active) {
                $this->fillable[] = $field->formattedTitle;
                $this->allAttributesFields[] = $field->formattedTitle;
                $this->filterFields[$field->formattedTitle] = 'true';

                if($field->pivot->required) {
                    $this->requiredFields[] = $field->formattedTitle;
                }

                $this->attributeType[$field->formattedTitle] = $this->getAttributeType($field);

                if($field->pivot->multiple) {
                    $this->multipleFields[$field->formattedTitle] = true;
                }
            }
        }
    }

    private function getAttributeType($field) {
        $attributeType = 'Models::AttributeType_Text';

        switch($field->field_type->name) {
            case AttributeFieldType::Integer:
                $attributeType = 'Models::AttributeType_Number';
                break;

            case AttributeFieldType::Date:
                $attributeType = 'Models::AttributeType_Date';
                break;

            default:
                $attributeType = 'Models::AttributeType_Text';
                break;
        }

        return $attributeType;
    }

    protected function populateContent() {
        $this->content = '<?php' . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'namespace App\NodeModel;' . PHP_EOL . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'use App\AppModel;' . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'use App\Constants\Models;' . PHP_EOL . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'class ' . $this->getModelClassName($this->model->name) . ' extends AppModel {' . PHP_EOL;
        
        $this->content .= str_repeat(' ', 8) . 'public $timestamps = false;' . PHP_EOL . PHP_EOL;

        $this->addFormattedList('fillable');
        $this->addFormattedList('allAttributesFields');
        $this->addFormattedList('requiredFields');
        $this->addFormattedListWithKeys('filterFields');
        $this->addFormattedList('defaultFieldsValues');
        $this->addFormattedListWithKeys('attributeType');
        $this->addFormattedListWithKeys('relationsSettings');
        $this->addFormattedListWithKeys('multipleFields');

        $this->content .= str_repeat(' ', 4) . '}' . PHP_EOL;
    }
}