<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\Models;
use App\Constants\FieldTypeCategory;
use App\Utils\Utils;

class Field extends AppModel {
    use SoftDeletes;

    protected $fillable = ['title'];

    protected $allFields = ['id', 'title', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $allFieldsFromPivots = ['active', 'required', 'multiple', 'sortable'];

    protected $requiredFields = ['title', 'fieldType'];
    
    protected $defaultFieldsValues = [
        'active' => '1'
    ];
    
    protected $defaultDropdownColumn = 'title';

    protected $attributeType = [
        'active' => Models::AttributeType_Checkbox,
        'required' => Models::AttributeType_Checkbox,
        'multiple' => Models::AttributeType_Checkbox,
        'sortable' => Models::AttributeType_Checkbox
    ];

    protected $relationsSettings = [
        'fieldType' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\FieldType',
            'foreignKey' => 'field_type_id',
            'filters' => ['category' => [FieldTypeCategory::Field]]
        ]
    ];

    protected $dependsOn = [];
    
    public function getFormattedNameAttribute() {
        return Utils::getFormattedDBName($this->title);
    }
}