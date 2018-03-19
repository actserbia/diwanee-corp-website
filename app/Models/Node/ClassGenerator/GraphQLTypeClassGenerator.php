<?php

namespace App\Models\Node\ClassGenerator;

use App\Constants\Settings;
use App\Constants\AttributeFieldType;

class GraphQLTypeClassGenerator extends ClassGenerator {
    const FOLDER = 'GraphQL/Type/NodeModel';

    protected $modelName;
    protected $fields = [];

    protected function getClassFilename($modelName) {
        return app_path() . '/' . static::FOLDER . '/' . $this->getModelClassName($modelName) . Settings::GraphQLTypeSufix . '.php';
    }

    protected function populateData() {
        $this->modelName = $this->getModelClassName($this->model->name);
        $this->fields = [];

        foreach($this->model->attribute_fields as $field) {
            if($field->pivot->active) {
                $this->fields[$field->formattedTitle] = [
                    'type' => AttributeFieldType::graphQLTypes[$field->field_type->name],
                    'required' => $field->pivot->required
                ];
            }
        }
    }

    protected function populateContent() {
        $this->content = '<?php' . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'namespace App\\GraphQL\\Type\\NodeModel;' . PHP_EOL . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'use App\\GraphQL\\Type\\' . ucfirst(Settings::NodeModelPrefix) . 'Node' . Settings::GraphQLTypeSufix . ';' . PHP_EOL . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'class ' . $this->getModelClassName($this->model->name) . Settings::GraphQLTypeSufix . ' extends ' . ucfirst(Settings::NodeModelPrefix) . 'Node' . Settings::GraphQLTypeSufix . ' {' . PHP_EOL;
        $this->content .= str_repeat(' ', 8) . 'protected $modelName = \'' . $this->modelName . '\';' . PHP_EOL;
        $this->addFormattedListWithKeys('fields');
        $this->content .= str_repeat(' ', 4) . '}' . PHP_EOL;
    }
}