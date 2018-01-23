<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class Type extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'status'];

    protected $fields = ['id', 'name', 'status', 'created_at', 'updated_at', 'deleted_at'];

    protected $required = ['name'];

    protected $relationsSettings = [
        'nodes' => [
            'relationType' => 'hasMany',
            'model' => 'App\\Node',
            'foreignKey' => 'type_id'
        ]
    ];

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
