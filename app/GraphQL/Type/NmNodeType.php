<?php
namespace App\GraphQL\Type;

use Illuminate\Support\Str;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use App\GraphQL\Type\Scalar\Timestamp;
use App\GraphQL\Type\Scalar\JsonData;

class NmNodeType extends GraphQLType {
    protected $modelName = 'NmNode';

    protected $fields = [];

    public function __construct($attributes = array()) {
        parent::__construct($attributes);

        $this->attributes['name'] = $this->modelName;
        $this->attributes['description'] = $this->modelName . ' type';
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
            $typeClass = $fieldSettings['type'][0];
            $typeFieldType = $fieldSettings['type'][1];
            
            switch($typeClass) {
                case 'JsonData':
                  $type = JsonData::$typeFieldType();
                  break;
                  
                case 'Timestamp':
                  $type = Timestamp::$typeFieldType();
                  break;
                
                default:
                    $type = Type::$typeFieldType();
                    break;
            }
            
            $fields[$fieldName] = [
                'type' => (isset($fieldSettings['required']) && $fieldSettings['required']) ? Type::nonNull($type) : $type,
                'description' => Str::studly($fieldName)
            ];
        }
        return $fields;
    }
}