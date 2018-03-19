<?php

namespace App\Models\Node\ClassGenerator;

use App\Constants\AttributeFieldType;

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
        foreach($this->model->attribute_fields as $field) {
            if($field->pivot->active) {
                $this->fillable[] = $field->formattedTitle;
                $this->allAttributesFields[] = $field->formattedTitle;
                $this->filterFields[$field->formattedTitle] = 'true';

                if($field->pivot->required) {
                    $this->requiredFields[] = $field->formattedTitle;
                }

                $this->attributeType[$field->formattedTitle] = AttributeFieldType::modelAttributeTypes[$field->field_type->name];
            }
        }
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