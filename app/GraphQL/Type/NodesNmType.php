<?php
namespace App\GraphQL\Type;

use App\Node;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class NmNodesType extends GraphQLType {
    protected $modelName = '';
    
    protected $fields = [];
    
    public function __construct($attributes = array()) {
        parent::__construct($attributes);
        
        $this->attributes['name'] = $this->modelName . 's';
        $this->attributes['description'] = $this->modelName . 's type';
        $this->attributes['model'] = 'App\\NodeModel\\' . $this->modelName;
    }

    public function fields() {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::int())
            ],
            'node_id' => [
                'type' => Type::nonNull(Type::int())
            ]
        ];
        
        foreach($this->fields as $fieldName => $fieldSettings) {
            $type = $fieldSettings['type'];
            $fields[$fieldName] = [
                'type' => $fieldSettings['required'] ? Type::nonNull(Type::$type()) : Type::$type(),
                'description' => $fieldSettings['description']
            ];
        }
        return $fields;
    }
}