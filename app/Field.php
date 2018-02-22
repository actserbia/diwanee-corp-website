<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\Models;
use App\Constants\FieldTypeCategory;
use App\Utils\Utils;
use App\Models\Node\NodeModelClassGenerator;
use App\Models\Node\NodeModelDBGenerator;
use App\Tag;

class Field extends AppModel {
    use SoftDeletes;

    protected $fillable = ['title'];

    protected $allAttributesFields = ['id', 'title', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $allFieldsFromPivots = ['active', 'required', 'multiple', 'multiple_list'];

    protected $requiredFields = ['title', 'field_type', 'attribute_field_type'];
    
    protected $defaultFieldsValues = [
        'active' => '1'
    ];
    
    protected $defaultDropdownColumn = 'title';

    protected $attributeType = [
        'active' => Models::AttributeType_Checkbox,
        'required' => Models::AttributeType_Checkbox,
        'multiple' => Models::AttributeType_Checkbox,
        'multiple_list' => Models::AttributeType_CheckboxList
    ];

    protected $relationsSettings = [
        'field_type' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\FieldType',
            'foreignKey' => 'field_type_id',
            'automaticRender' => true
        ],
        'attribute_field_type' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\FieldType',
            'foreignKey' => 'field_type_id',
            'filters' => ['category' => [FieldTypeCategory::Attribute]]
        ]
    ];

    protected $dependsOn = [];
    
    public function getFormattedTitleAttribute() {
        return Utils::getFormattedDBName($this->title);
    }

    public function saveData(array $data) {
        $oldTitle = $this->formattedTitle;

        parent::saveData($data);

        if(!empty($oldTitle) && $oldTitle !== $this->title) {
            NodeModelDBGenerator::changeFieldNameInAllNodeTables($oldTitle, $this->formattedTitle);
            NodeModelClassGenerator::generateAll();
        }
    }
    
    public function attributeValue($field) {
        $value = parent::attributeValue($field);
        if($field === 'multiple_list' && $this->field_type->category === FieldTypeCategory::Tag) {
            $tags = Tag::where('tag_type_id', '=', $this->field_type->id)->get();
            $maxLevelsCount = Tag::relationMaxLevelsCount('children', $tags);
            for($index = count($value); $index < $maxLevelsCount; $index++) {
                $value[] = false;
            }
        }
        return $value;
    }
    
    public function checkIfCanRemove() {
        if(isset($this->pivot) && $this->pivot->pivotParent->modelClass === 'App\\NodeType') {
            return $this->pivot->pivotParent->checkIfCanRemoveSelectedRelationItem($this->field_type->category . '_fields');
        }
        
        return true;
    }
}