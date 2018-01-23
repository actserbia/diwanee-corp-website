<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'status'];

    protected $required = ['name'];


    public function saveModel(array $data) {
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
