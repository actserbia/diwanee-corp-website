<?php

namespace App\Models\Node\ClassGenerator;

use App\Constants\Settings;
use App\Constants\AttributeFieldType;
use App\Utils\Utils;
use App\Constants\FieldTypeCategory;

class GraphQLQueryClassGenerator extends ClassGenerator {
    protected $folder = 'GraphQL/Query/NodeModel';

    protected $name;
    protected $args = [];

    protected function getClassFilename($modelName) {
        return app_path() . '/' . $this->folder . '/' . $this->getModelClassName($modelName) . 'sQuery.php';
    }

    protected function populateData() {
        $this->name = Utils::getFormattedName($this->model->name, ' ');
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
        $this->content .= str_repeat(' ', 4) . 'use App\\GraphQL\\Query\\' . ucfirst(Settings::NodeModelPrefix) . 'NodesQuery;' . PHP_EOL . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'class ' . $this->getModelClassName($this->model->name) . 'sQuery extends ' . ucfirst(Settings::NodeModelPrefix) . 'NodesQuery {' . PHP_EOL;
        $this->content .= str_repeat(' ', 8) . 'protected $name = \'' . $this->name . '\';' . PHP_EOL;
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

            default:
                $attributeType = 'string';
                break;
        }

        return $attributeType;
    }
}