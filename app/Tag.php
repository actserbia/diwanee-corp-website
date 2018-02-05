<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Constants\Models;
use Illuminate\Support\Facades\DB;
use App\Models\ModelDataManager;

class Tag extends Model {

    use SoftDeletes;
    use ModelDataManager;
    
    protected $fillable = ['name'];

    protected $fields = ['id', 'name', 'created_at', 'updated_at', 'deleted_at'];

    protected $required = ['name', 'tagType'];

    protected $attributeType = [
        'parent_id' => Models::AttributeType_Number,
        'tag_id' => Models::AttributeType_Number
    ];
    
    protected $defaultDropdownColumn = 'name';

    protected $relationsSettings = [
        'tagType' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\TagType',
            'foreignKey' => 'tag_type_id'
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
        ]
    ];
    
    protected $multiple = ['parents', 'children'];
    
    protected $dependsOn = [
        'parents' => ['tagType'],
        'children' => ['tagType']
    ];

    public function parentsRelationValues($dependsOnValues = null) {
        $tagType = $this->getDependsOnValue('tagType', $dependsOnValues);
        return isset($tagType->id) ? Tag::where('tag_type_id', '=', $tagType->id)->where('id', '!=', $this->id)->get() : [];
    }

    public function childrenRelationValues($dependsOnValues = null) {
        $tagType = $this->getDependsOnValue('tagType', $dependsOnValues);
        return isset($tagType->id) ? Tag::where('tag_type_id', '=', $tagType->id)->where('id', '!=', $this->id)->get() : [];
    }

    public function saveTag(array $data) {
        DB::beginTransaction();
        try {
            $this->fill($data);
            $this->populateBelongsToRelations($data);
            $this->save();

            $this->saveBelongsToManyRelations($data);

            DB::commit();
            return true;

        } catch(Exception $e) {
            DB::rollBack();
            return false;
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
}