<?php

namespace App\Models\Node;

use App\Constants\Settings;
use App\Constants\AttributeFieldType;
use App\Utils\Utils;
use App\Utils\FileFunctions;
use App\NodeType;
use App\Constants\FieldTypeCategory;

class NodeModelGraphQLClassGenerator {
    private $model = null;
    private $typeFilepath = null;
    private $queryFilepath = null;
    private $oldTypeFilepath = null;
    private $oldQueryFilepath = null;

    protected $modelName;
    protected $fields = [];
    protected $name;
    protected $args = [];

    private $typeContent = '';
    private $queryContent = '';

    public function __construct($model, $oldNodeTypeName = null) {
        $this->model = $model;

        if(isset($oldNodeTypeName)) {
            $this->oldTypeFilepath = app_path() . '/GraphQL/Type/' . $this->getClassName($oldNodeTypeName, 'type') . '.php';
            $this->oldQueryFilepath = app_path() . '/GraphQL/Query/' . $this->getClassName($oldNodeTypeName, 'query') . '.php';
        }

        $this->typeFilepath = app_path() . '/GraphQL/Type/' . $this->getClassName($model->name, 'type') . '.php';
        $this->queryFilepath = app_path() . '/GraphQL/Query/' . $this->getClassName($model->name, 'query') . '.php';
    }

    private function getClassName($modelName, $type) {
        return ucfirst(Settings::NodeModelPrefix) . Utils::getFormattedName($modelName, ' ') . 's' . ucfirst($type);
    }

    public function generate() {
        if(isset($this->oldFilepath) && $this->oldFilepath !== $this->filepath) {
            FileFunctions::deleteFile($this->oldFilepath);
        }

        $this->populateData();

        $this->populateContent();

        FileFunctions::writeToFile($this->content, $this->filepath);
    }

    private function populateData() {
        $this->modelName = $this->model->name;
        $this->fields = [];
        $this->name = $this->model->name;
        $this->args = [];

        $attributeFieldsRelationName = FieldTypeCategory::Attribute . '_fields';
        foreach($this->model->$attributeFieldsRelationName as $field) {
            if($field->pivot->active) {
                $this->fields[$field->formattedTitle] = [
                    'type' => 'string',
                    'required' => $field->pivot->required
                ];

                $this->args[$field->formattedTitle] = $this->getAttributeType($field);
            }
        }
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

    private function populateContent() {
        $this->content = '<?php' . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'class ' . $this->getClassName($this->model->name) . ' extends AppModel {' . PHP_EOL;

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

    private function addFormattedList($listName) {
        if(!empty($this->$listName)) {
            $this->content .= str_repeat(' ', 8) . 'protected $' . $listName . ' = [\'' . implode('\', \'', $this->$listName) . '\']' . ';' . PHP_EOL . PHP_EOL;
        }
    }

    private function addFormattedListWithKeys($listName) {
        if(!empty($this->$listName)) {
            $this->content .= str_repeat(' ', 8) . 'protected $' . $listName . ' = [' . PHP_EOL;

            foreach($this->$listName as $listItemKey => $listItemValue) {
                $this->content .= $this->addFormattedListWithKeysItem($listItemKey, $listItemValue);
            }

            $this->content .= str_repeat(' ', 8) . '];' . PHP_EOL . PHP_EOL;
        }
    }

    private function addFormattedListWithKeysItem($listItemKey, $listItemValue) {
        $this->content .= str_repeat(' ', 12) . '\'' . $listItemKey . '\' => ';
        if(is_array($listItemValue)) {
            $this->content .= '[' . PHP_EOL;
            foreach($listItemValue as $key => $value) {
                $this->content .= str_repeat(' ', 16) . '\'' . $key . '\' => \'' . addslashes($value) . '\',' . PHP_EOL;
            }
            $this->content .= str_repeat(' ', 12) . ']';
        } else {
            $this->content .= $listItemValue;
        }
        $this->content .= ',' . PHP_EOL;
    }

    public static function generateAll() {
        $nodeTypes = NodeType::get();
        foreach($nodeTypes as $nodeType) {
            $generator = new self($nodeType);
            $generator->generate();
        }
    }

    public static function deleteAll() {
        $folder = app_path() . '/NodeModel';
        $files = glob($folder . '/*');
        foreach($files as $file) {
            if(is_file($file)) {
                unlink($file);
            }
        }
    }
}