<?php

namespace App\Models;

use App\Constants\FieldType;
use App\Utils\FileFunctions;

class NodeModelGenerator {
    private $model = null;
    
    private $filepath = '';

    private $fillable = [];
    private $allFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
    private $requiredFields = [];
    private $defaultFieldsValues = [];
    private $attributeType = [];
    
    private $content = '';
    
    public function __construct($model) {
        $this->model = $model;
        $this->filepath = self::getFilepath($model->name);
    }
    
    public function generate() {
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
        $this->content .= str_repeat(' ', 4) . 'class ' . $this->model->name . ' extends AppModel {' . PHP_EOL;
        $this->content .= str_repeat(' ', 8) . 'use SoftDeletes;' . PHP_EOL . PHP_EOL;
        $this->addFormattedList('fillable');
        $this->addFormattedList('allFields');
        $this->addFormattedList('requiredFields');
        $this->addFormattedList('defaultFieldsValues');
        $this->addFormattedListWithKeys('attributeType');
        $this->content .= str_repeat(' ', 4) . '}' . PHP_EOL;
    }
    
    private function addFormattedList($listName) {
        if(!empty($this->$listName)) {
            $this->content .= str_repeat(' ', 8) . 'protected $' . $listName . ' = [\'' . implode('\', \'', $this->$listName) . '\']' . ';' . PHP_EOL . PHP_EOL;
        }
    }
    
    private function addFormattedListWithKeys($listName) {
        if(!empty($this->$listName)) {
            $this->content .= str_repeat(' ', 8) . 'protected $attributeType = ' . '[' . PHP_EOL;

            foreach($this->$listName as $key => $value) {
                $this->content .= str_repeat(' ', 12) . '\'' . $key . '\' => ' . $value . ',' . PHP_EOL;
            }

            $this->content .= str_repeat(' ', 8) . ']'  . ';' . PHP_EOL . PHP_EOL;
        }
    }
    
    public static function delete($modelName) {
        FileFunctions::deleteFile(self::getFilepath($modelName));
    }
    
    private static function getFilepath($modelName) {
        return app_path() . '/NodeModel/' . $modelName . '.php';
    }
}