<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\Models;
use App\Models\Node\NodeModelManager;

class Node extends AppModel {
    use SoftDeletes;
    use NodeModelManager;

    protected $allFields = ['id', 'title', 'status', 'node_type_id', 'author_id', 'created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['title', 'status', 'node_type_id', 'author_id'];

    protected $requiredFields = ['title', 'status', 'node_type_id'];

    protected $attributeType = [
        'status' => Models::AttributeType_Enum,
        'author_id' => Models::AttributeType_Number,
        'node_type_id' => Models::AttributeType_Number
    ];

    protected $defaultDropdownColumn = 'title';

    protected $relationsSettings = [
        'nodeType' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\NodeType',
            'foreignKey' => 'node_type_id'
        ],
        'author' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\User',
            'foreignKey' => 'author_id'
        ],
        'tags' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Tag',
            'pivot' => 'node_tag',
            'foreignKey' => 'node_id',
            'relationKey' => 'tag_id',
            'sortBy' => 'ordinal_number'
        ]
    ];

    protected $multipleRelations = ['tags'];
}