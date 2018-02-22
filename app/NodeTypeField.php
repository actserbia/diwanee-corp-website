<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class NodeTypeField extends Pivot {
    public $pivotParent = 'App\\Field';
    protected $foreignKey = 'node_type_id';
    protected $relatedKey = 'field_id';
    
    protected $casts = [
        'multiple_list' => 'array'
    ];
}