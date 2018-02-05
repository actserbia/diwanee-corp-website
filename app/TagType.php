<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;
use App\Models\ModelDataManager;

class TagType extends Model {

    use SoftDeletes;
    use ModelDataManager;
    
    protected $fillable = ['name'];

    protected $fields = ['id', 'name', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $attributeType = [];

    protected $required = ['name'];
    
    protected $defaultDropdownColumn = 'name';

    protected $relationsSettings = [
        'tags' => [
            'relationType' => 'hasMany',
            'model' => 'App\\Tag',
            'foreignKey' => 'tag_type_id',
            'relationKey' => 'id'
        ]
    ];
    
    protected $multiple = ['tags'];

    protected $dependsOn = [];

    public function saveTagType(array $data) {
        DB::beginTransaction();
        try {
            $this->fill($data);
            $this->populateBelongsToRelations($data);
            $this->save();

            DB::commit();
            return true;

        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}