<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use App\NodeType;
use App\Constants\Models;
use App\Utils\Utils;

class Node extends AppModel {
    use SoftDeletes;

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
    
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        
        if(isset($attributes['node_type_id'])) {
            $this->populateData($attributes['node_type_id']);
        }
    }
    
    public static function __callStatic($method, $parameters) {
        if(in_array($method, ['findOrFail', 'find'])) {
            $object = (new static)->$method(...$parameters);
            $object->populateData();
            return $object;
        } elseif(in_array($method, ['where'])) {
            $object = (new static)->$method(...$parameters);
            return $object;
        } else {
            return (new static)->$method(...$parameters);
        }
    }
    
    public function populateData($nodeTypeId = null) {
        $this->populateTagFieldsData($nodeTypeId);
    }
    
    private function populateTagFieldsData($nodeTypeId = null) {
        $nodeType = isset($this->nodeType) ? $this->nodeType : NodeType::find($nodeTypeId);
        foreach($nodeType->tags as $tagField) {
            $relationSettings = [
                'parent' => 'tags',
                'filters' => ['tag_type_id' => [$tagField->field_type_id]],
                'fillable' => true
            ];
            $this->relationsSettings[Utils::getFormattedDBName($tagField->title)] = $relationSettings;
        }
    }

    public function saveData(array $data) {
        $data['author'] = isset($this->id) ? $this->author->id : Auth::id();
            
        parent::saveData($data);
    }
}
