<?php
namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\Models;
use App\Constants\FieldTypeCategory;
use App\Constants\Settings;
use App\Constants\Database;
use App\Models\Node\ClassGenerator\ClassGenerator;
use App\Models\Node\NodeModelDBGenerator;
use App\Tag;

class Field extends AppModel {
    use SoftDeletes;

    protected $fillable = ['title'];

    protected $allAttributesFields = ['id', 'title', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $allFieldsFromPivots = ['active', 'required', 'multiple', 'render_type'];

    protected $requiredFields = ['title', 'field_type', 'attribute_field_type', 'render_type'];
    
    protected $defaultFieldsValues = [
        'active' => '1',
        'multiple' =>  [
            'hierarchy' => true,
            'value' => []
        ]
    ];
    
    protected $defaultDropdownColumn = 'title';

    protected $attributeType = [
        'active' => Models::AttributeType_Checkbox,
        'required' => Models::AttributeType_Checkbox,
        'multiple' => Models::AttributeType_CheckboxList,
        'render_type' => Models::AttributeType_Enum
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
        ],
        'tag_field_type' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\FieldType',
            'foreignKey' => 'field_type_id',
            'filters' => ['category' => [FieldTypeCategory::Tag]]
        ]
    ];

    protected $dependsOn = [];
    
    public function getFormattedTitleAttribute() {
        return Str::snake($this->title);
    }

    public function getFieldTypeCategoryAttribute() {
        return $this->field_type->category;
    }

    public function saveData(array $data) {
        $oldTitle = $this->formattedTitle;

        parent::saveData($data);

        if(!empty($oldTitle) && $oldTitle !== $this->title) {
            NodeModelDBGenerator::changeFieldNameInAllNodeTables($oldTitle, $this->formattedTitle);
            ClassGenerator::generateAllFiles();
        }
    }
    
    public function attributeValue($field) {
        $value = parent::attributeValue($field);
        if($field === 'multiple' && $value['hierarchy']) {
            $maxLevelsCount = $this->getMaxLevelsCount();
            for($index = count($value['value']); $index < $maxLevelsCount; $index++) {
                $value['value'][] = false;
            }
        }
        return $value;
    }
    
    private function getMaxLevelsCount() {
        if($this->id === Database::Field_Relation_Tag_Id) {
            $tags = Tag::get();
        } elseif($this->field_type->category === FieldTypeCategory::Tag) {
            $tags = Tag::where('tag_type_id', '=', $this->field_type->id)->get();
        }

        return ($tags !== null) ? Tag::relationMaxLevelsCount('children', $tags) : 1;
    }

    public function checkIfCanRemove() {
        if(isset($this->pivot) && $this->pivot->pivotParent->modelClass === 'App\\NodeType') {
            return $this->pivot->pivotParent->checkIfCanRemoveSelectedRelationItem($this->field_type->category . '_fields');
        }
        
        return true;
    }
    
    public function getMaximumCheckboxItemsCount($field) {
        if($field === 'multiple' && ($this->field_type->category === FieldTypeCategory::Tag || $this->id === Database::Field_Relation_Tag_Id)) {
            return Settings::MaximumTagsLevelsCount;
        }
        
        return true;
    }
}