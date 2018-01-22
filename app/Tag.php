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

    protected $fields = ['id', 'name', 'tag_type_id', 'created_at', 'updated_at', 'deleted_at'];

    protected $required = ['name', 'tag_type_id'];

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
        return isset($tagType->parentType->id) ? Tag::where('tag_type_id', '=', $tagType->parentType->id)->get() : [];
    }

    public function childrenRelationValues($dependsOnValues = null) {
        $tagType = $this->getDependsOnValue('tagType', $dependsOnValues);
        return isset($tagType->subtype->id) ? Tag::where('tag_type_id', '=', $tagType->subtype->id)->get() : [];
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
}