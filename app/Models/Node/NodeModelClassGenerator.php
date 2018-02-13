<?php

namespace App\Models\Node;

use App\Constants\FieldType;
use App\Utils\Utils;
use App\Utils\FileFunctions;

class NodeModelClassGenerator {
    private $model = null;
    private $oldNodeTypeName = null;

    private $filepath = '';

    private $fillable = [];
    private $allFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
    private $requiredFields = [];
    private $defaultFieldsValues = [];
    private $attributeType = [];

    private $relationsSettings = [
        'node' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\Node',
            'foreignKey' => 'node_id'
        ]
    ];

    private $content = '';

    public function __construct($model, $oldNodeTypeName) {
        $this->model = $model;
        $this->oldNodeTypeName = $oldNodeTypeName;

        $this->filepath = app_path() . '/NodeModel/' . Utils::getFormattedName($model->name, ' ') . '.php';
    }

    public function generate() {
        if($this->oldNodeTypeName !== $this->model->nodeTypeName) {
            $filepath = app_path() . '/NodeModel/' . Utils::getFormattedName($this->oldNodeTypeName, ' ') . '.php';
            FileFunctions::deleteFile($filepath);
        }

        $this->populateData();

        $this->populateContent();

        FileFunctions::writeToFile($this->content, $this->filepath);
    }

    private function populateData() {
        foreach($this->model->fields as $field) {
            $this->fillable[] = $field->formattedName;
            $this->allFields[] = $field->formattedName;
            if($field->pivot->required) {
                $this->requiredFields[] = $field->formattedName;
            }

            $this->attributeType[$field->formattedName] = $this->getAttributeType($field);
        }
    }

    private function getAttributeType($field) {
        $attributeType = 'Models::AttributeType_Text';

        switch($field->fieldType->name) {
            case FieldType::Integer:
                $attributeType = 'Models::AttributeType_Number';
                break;

            case FieldType::Date:
                $attributeType = 'Models::AttributeType_Date';
                break;

            default:
                $attributeType = 'Models::AttributeType_Text';
                break;
        }

        return $attributeType;
    }

    private function populateContent() {
        $this->content = '<?php' . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'namespace App\NodeModel;' . PHP_EOL . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'use App\AppModel;' . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'use Illuminate\Database\Eloquent\SoftDeletes;' . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'use App\Constants\Models;' . PHP_EOL . PHP_EOL;
        $this->content .= str_repeat(' ', 4) . 'class ' . Utils::getFormattedName($this->model->name, ' ') . ' extends AppModel {' . PHP_EOL;
        $this->content .= str_repeat(' ', 8) . 'use SoftDeletes;' . PHP_EOL . PHP_EOL;

        $this->addFormattedList('fillable');
        $this->addFormattedList('allFields');
        $this->addFormattedList('requiredFields');
        $this->addFormattedList('defaultFieldsValues');
        $this->addFormattedListWithKeys('attributeType');
        $this->addFormattedListWithKeys('relationsSettings');

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
}