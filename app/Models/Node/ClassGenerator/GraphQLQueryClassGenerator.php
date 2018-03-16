<?php

namespace App\Models\Node\ClassGenerator;

use App\Constants\Settings;
use App\Constants\AttributeFieldType;
use App\Constants\FieldTypeCategory;
use Illuminate\Support\Str;

class GraphQLQueryClassGenerator extends ClassGenerator {
    const FOLDER = 'GraphQL/Query/NodeModel';

    protected $modelName;
    protected $args = [];

    protected function getClassFilename($modelName) {
        return app_path() . '/' . static::FOLDER . '/' . $this->getModelClassName($modelName) . Settings::GraphQLQuerySufix . '.php';
    }

    protected function populateData() {
        $this->modelName = 'App\\NodeModel\\' . Str::studly($this->model->name);
        $this->args = [];

        $attributeFieldsRelationName = FieldTypeCategory::Attribute . '_fields';
        foreach($this->model->$attributeFieldsRelationName as $field) {
            if($field->pivot->active) {
                $this->args[$field->formattedTitle] = $this->getAttributeType($field);
            }
        }
    }

    protected function populateContent() {
        $this->content = '<?php' . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'namespace App\\GraphQL\\Query\\NodeModel;' . PHP_EOL . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'use App\\GraphQL\\Query\\' . ucfirst(Settings::NodeModelPrefix) . 'Node' . Settings::GraphQLQuerySufix . ';' . PHP_EOL . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'class ' . $this->getModelClassName($this->model->name) . Settings::GraphQLQuerySufix . ' extends ' . ucfirst(Settings::NodeModelPrefix) . 'Node' . Settings::GraphQLQuerySufix . ' {' . PHP_EOL;
        $this->content .= str_repeat(' ', 8) . 'protected $modelName = \'' . $this->modelName . '\';' . PHP_EOL;
        $this->addFormattedListWithKeys('args');
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

            case AttributeFieldType::Boolean:
                $attributeType = 'boolean';
                break;

            default:
                $attributeType = 'string';
                break;
        }

        return $attributeType;
    }
}