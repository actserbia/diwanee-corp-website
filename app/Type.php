<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\ModelDataManager;
use App\Constants\Models;
use App\Constants\TypeStatus;


class Type extends Model
{
    use SoftDeletes;
    use ModelDataManager;

    protected $fillable = ['name', 'status'];

    protected $fields = ['id', 'name', 'status', 'created_at', 'updated_at', 'deleted_at'];

    protected $required = ['name', 'status'];

    protected $attributeType = [
        'status' => Models::AttributeType_Enum,
    ];

    protected $relationsSettings = [
        'nodes' => [
            'relationType' => 'hasMany',
            'model' => 'App\\Node',
            'foreignKey' => 'type_id'
        ]
    ];

    public function scopeWithActive($query) {
        $query->whereIn('status', TypeStatus::activeStatuses);
    }

    protected $dependsOn = [];

    public function saveType(array $data) {
        DB::beginTransaction();
        try {
            $this->fill($data);
            $this->save();

            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
