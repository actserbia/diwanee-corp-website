<?php

namespace App\Models\Node\ClassGenerator;

use App\Constants\Settings;
use App\Constants\AttributeFieldType;
use App\Constants\FieldTypeCategory;

class GraphQLTypeClassGenerator extends ClassGenerator {
    protected $folder = 'GraphQL/Type/NodeModel';

    protected $modelName;
    protected $fields = [];

    protected function getClassFilename($modelName) {
        return app_path() . '/' . $this->folder . '/' . $this->getModelClassName($modelName) . 'sType.php';
    }

    protected function populateData() {
        $this->modelName = $this->getModelClassName($this->model->name);
        $this->fields = [];

        $attributeFieldsRelationName = FieldTypeCategory::Attribute . '_fields';
        foreach($this->model->$attributeFieldsRelationName as $field) {
            if($field->pivot->active) {
                $this->fields[$field->formattedTitle] = [
                    'type' => $this->getAttributeType($field),
                    'required' => $field->pivot->required
                ];
            }
        }
    }

    protected function populateContent() {
        $this->content = '<?php' . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'namespace App\\GraphQL\\Type\\NodeModel;' . PHP_EOL . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'use App\\GraphQL\\Type\\' . ucfirst(Settings::NodeModelPrefix) . 'NodesType;' . PHP_EOL . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'class ' . $this->getModelClassName($this->model->name) . 'sType extends ' . ucfirst(Settings::NodeModelPrefix) . 'NodesType {' . PHP_EOL;
        $this->content .= str_repeat(' ', 8) . 'protected $modelName = \'' . $this->modelName . '\';' . PHP_EOL;
        $this->addFormattedListWithKeys('fields');
        $this->content .= str_repeat(' ', 4) . '}' . PHP_EOL;
    }

    private function getAttributeType($field) {
        $attributeType = 'string';

        switch($field->field_type->name) {
            case AttributeFieldType::Integer:
                $attributeType = 'int';
                break;

            case AttributeFieldType::Date:
                $attributeType = 'date';
                break;

            default:
                $attributeType = 'string';
                break;
        }

        return $attributeType;
    }
}