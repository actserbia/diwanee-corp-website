<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Constants\Models;
use App\Constants\Database;
use Illuminate\Support\Facades\DB;
use App\Constants\FieldTypeCategory;

class Tag extends AppModel {
    use SoftDeletes;
    
    protected $fillable = ['name', 'meta_title', 'meta_description'];

    protected $allAttributesFields = ['id', 'name', 'tag_type_id', 'meta_title', 'meta_description', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $allFieldsFromPivots = ['tag_id', 'parent_id'];

    protected $requiredFields = ['name', 'tag_type'];
    
    protected $filterFields = [
        'id' => false,
        'name' => true,
        'meta_title' => true,
        'meta_description' => true,
        'tag_type:name' => true,
        'created_at' => true,
        'updated_at' => true,
        'deleted_at' => false,
        'parents:parent_id' => false,
        'parents:name' => false,
        'parents:created_at' => false,
        'parents:updated_at' => false,
        'parents:deleted_at' => false,
        'children:tag_id' => false,
        'children:name' => false,
        'children:created_at' => false,
        'children:updated_at' => false,
        'children:deleted_at' => false,
        'nodes:title' => true,
        'nodes:status' => true,
        'nodes:created_at' => false,
        'nodes:updated_at' => false,
        'nodes:deleted_at' => false
    ];
    
    protected $statisticFields = [
        'tag_type:name',
        'parents:name',
        'children:name'
    ];

    protected $attributeType = [
        'parent_id' => Models::AttributeType_Number,
        'tag_id' => Models::AttributeType_Number
    ];
    
    protected $defaultDropdownColumn = 'name';

    protected $relationsSettings = [
        'tag_type' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\FieldType',
            'foreignKey' => 'tag_type_id',
            'filters' => ['category' => [FieldTypeCategory::Tag]]
        ],
        'tag_data' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Node',
            'pivot' => 'node_tag',
            'foreignKey' => 'tag_id',
            'relationKey' => 'node_id',
            'filters' => ['node_type_id' => [Database::NodeType_TagData_Id]]
        ],
        'parents' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Tag',
            'pivot' => 'tag_parent',
            'foreignKey' => 'tag_id',
            'relationKey' => 'parent_id'
        ],
        'children' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Tag',
            'pivot' => 'tag_parent',
            'foreignKey' => 'parent_id',
            'relationKey' => 'tag_id'
        ],
        'nodes' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Node',
            'pivot' => 'node_tag',
            'foreignKey' => 'tag_id',
            'relationKey' => 'node_id',
            'automaticSave' => false
        ]
    ];
    
    protected $multipleFields = [
        'parents' => true,
        'children' => true,
        'nodes' => true
    ];
    
    protected $dependsOn = [
        'parents' => ['tag_type'],
        'children' => ['tag_type']
    ];
    
    protected $categoryField = 'tag_type';
    
    public function getMovingDisabledAttribute() {
        if(count($this->parents) > 1) {
            return true;
        }

        $count = count($this->nodes);
        if($count > 0) {
            foreach($this->nodes as $node) {
                if($node->model_type->id === Database::NodeType_TagData_Id) {
                    $count--;
                }
            }
        }

        return ($count > 0) ? true : false;
    }
    
    public function parentsRelationValues($dependsOnValues = null) {
        $fieldType = $this->getDependsOnValue('tag_type', $dependsOnValues);
        return isset($fieldType->id) ? Tag::where('tag_type_id', '=', $fieldType->id)->where('id', '!=', $this->id)->get() : [];
    }

    public function childrenRelationValues($dependsOnValues = null) {
        $fieldType = $this->getDependsOnValue('tag_type', $dependsOnValues);
        return isset($fieldType->id) ? Tag::where('tag_type_id', '=', $fieldType->id)->where('id', '!=', $this->id)->get() : [];
    }

    public static function reorder($tagsData) {
        DB::beginTransaction();
        try {
            $tagIds = self::getTagsIds($tagsData);
            DB::table('tag_parent')->whereIn('tag_id', $tagIds)->delete();
            DB::table('tag_parent')->whereIn('parent_id', $tagIds)->delete();

            self::insertTagChildrens(0, $tagsData);

            DB::commit();
            return true;

        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private static function getTagsIds($tagsData) {
        $tagIds = [];
        foreach($tagsData as $tagData) {
            if(isset($tagData['children']) && count($tagData['children']) > 0) {
              $tagIds = array_merge($tagIds, self::getTagsIds($tagData['children']));
            }
            $tagIds[] = $tagData['id'];
        }
        return $tagIds;
    }

    private static function insertTagChildrens($parentId, $childrenData) {
        foreach($childrenData as $tagData) {
            if($parentId > 0) {
                DB::table('tag_parent')->insert([
                    'tag_id' => $tagData['id'],
                    'parent_id' => $parentId
                ]);
            }

            if(isset($tagData['children']) && count($tagData['children']) > 0) {
                self::insertTagChildrens($tagData['id'], $tagData['children']);
            }
        }
    }
    
    public function relationIds($relation) {
        $ids = [];

        foreach($this->$relation as $relationNode) {
            $ids[] = $relationNode->id;
            $ids = array_merge($ids, $relationNode->relationIds($relation));
        }

        return $ids;
    }
    
    public function relationLevelsCount($relation) {
        $levelsCount = 1;
        
        if(count($this->$relation) > 0) {
            $maxLevelsCount = 0;
            foreach($this->$relation as $relationNode) {
                if($maxLevelsCount < $relationNode->relationLevelsCount($relation)) {
                    $maxLevelsCount = $relationNode->relationLevelsCount($relation);
                }
            }
            
            $levelsCount = $maxLevelsCount + 1;
        }

        return $levelsCount;
    }
    
    public static function relationMaxLevelsCount($relation, $tags) {
        $maxLevelsCount = 0;
        
        foreach($tags as $tag) {
            if($maxLevelsCount < $tag->relationLevelsCount($relation)) {
                $maxLevelsCount = $tag->relationLevelsCount($relation);
            }
        }

        return $maxLevelsCount;
    }
    
    public static function tagsListMaxLevelsCount($tagsList) {
        $levelsCount = 0;
        
        if(!empty($tagsList)) {
            $maxLevelsCount = 0;
            
            foreach($tagsList as $tagData) {
                if(isset($tagData['children']) && $maxLevelsCount < self::tagsListMaxLevelsCount($tagData['children'])) {
                    $maxLevelsCount = self::tagsListMaxLevelsCount($tagData['children']);
                }
            }
            
            $levelsCount = $maxLevelsCount + 1;
        }
        
        return $levelsCount;
    }
    
    protected function checkIfCanRemoveRelationItem($relation) {
        if($relation === 'parents') {
            return (count($this->nodes) === 0);
        }
        
        return true;
    }
}