<?php

namespace App\Models;

use App\Constants\FieldType;
use App\Utils\FileFunctions;

class NodeModelGenerator {

    private $model = null;
    private $modelName = '';
    
    private $filepath = '';

    private $fillable = [];
    private $allFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
    private $requiredFields = [];
    private $defaultFieldsValues = [];
    private $content = '';
    
    public function __construct($model) {
        $this->model = $model; 
        $this->modelName = Utils::getFormattedName(Utils::getFormattedDBName($model->name));
        $this->filepath = app_path() . '/NodeModel/' . $this->modelName . '.php';
    }
    
    public function generate() {
        $attributeTypeList = '[' . PHP_EOL;
        foreach($this->fields as $field) {
            $fillable[] = $field->formattedName;
            $allFields[] = $field->formattedName;
            if($field->pivot->required) {
                $requiredFields[] = $field->formattedName;
            }
            
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
            $attributeTypeList .= str_repeat(' ', 12) . '\'' . $field->formattedName . '\' => ' . $attributeType . ',' . PHP_EOL;
        }
        $attributeTypeList .= str_repeat(' ', 8) . '];';
        
        
        
        $content = '<?php' . PHP_EOL;
        $content .= str_repeat(' ', 4) . 'namespace App\NodeModel;' . PHP_EOL . PHP_EOL;
        $content .= str_repeat(' ', 4) . 'use App\AppModel;' . PHP_EOL;
        $content .= str_repeat(' ', 4) . 'use Illuminate\Database\Eloquent\SoftDeletes;' . PHP_EOL;
        $content .= str_repeat(' ', 4) . 'use App\Constants\Models;' . PHP_EOL . PHP_EOL;
        $content .= str_repeat(' ', 4) . 'class ' . $this->modelName . ' extends AppModel {' . PHP_EOL;
        $content .= str_repeat(' ', 8) . 'use SoftDeletes;' . PHP_EOL . PHP_EOL;
        $content .= str_repeat(' ', 8) . 'protected $fillable = [\'' . implode('\', \'', $fillable) . '\'];' . PHP_EOL . PHP_EOL;
        $content .= str_repeat(' ', 8) . 'protected $allFields = [\'' . implode('\', \'', $allFields) . '\'];' . PHP_EOL . PHP_EOL;
        $content .= str_repeat(' ', 8) . 'protected $requiredFields = [\'' . implode('\', \'', $requiredFields) . '\'];' . PHP_EOL . PHP_EOL;
        $content .= str_repeat(' ', 8) . 'protected $defaultFieldsValues = [\'' . implode('\', \'', $defaultFieldsValues) . '\'];' . PHP_EOL . PHP_EOL;
        $content .= str_repeat(' ', 8) . 'protected $attributeType = ' . $attributeTypeList . PHP_EOL . PHP_EOL;
        $content .= str_repeat(' ', 4) . '}' . PHP_EOL;


        FileFunctions::writeToFile($content, $this->filepath);
    }
}