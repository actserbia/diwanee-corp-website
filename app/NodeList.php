<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\Models;
use App\Constants\FieldTypeCategory;


class NodeList extends AppModel {
    use SoftDeletes;

    protected $allAttributesFields = ['id', 'name', 'node_type_id', 'order_by_field_id', 'order', 'limit', 'created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['name', 'node_type_id', 'order_by_field_id', 'order', 'limit'];

    protected $requiredFields = ['name', 'node_type_id', 'order_by_field_id'];

    protected $attributeType = [
        'node_type_id' => Models::AttributeType_Number,
        'order_by_field_id' => Models::AttributeType_Number,
        'order' => Models::AttributeType_Checkbox,
        'limit' => Models::AttributeType_Number
    ];

    protected $defaultDropdownColumn = 'name';

    protected $relationsSettings = [
        'node_type' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\NodeType',
            'foreignKey' => 'node_type_id'
        ],
        'order_by_field' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\Field',
            'foreignKey' => 'order_by_field_id',
            'filters' => ['field_type.category' => [FieldTypeCategory::Attribute]]
        ]
    ];
}