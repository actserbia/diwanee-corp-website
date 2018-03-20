<?php
namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Type\Scalar\Timestamp;

class NmNodeQuery extends AppQuery {
    protected $modelName = '';

    protected $args = [];

    public function __construct($attributes = array()) {
        parent::__construct($attributes);

        $this->attributes['name'] = $this->modelName . ' Query';
        $this->attributes['description'] = 'A query';
    }

    public function type() {
        return Type::listOf(GraphQL::type($this->modelName));
    }

    public function args() {
        $args = [
            'id' => [
                'type' => Type::listOf(Type::int()),
                'name' => 'id'
            ],
            'node_id' => [
                'type' => Type::int(),
                'name' => 'node_id'
            ]
        ];

        foreach($this->args as $argName => $argSettings) {
            $typeClass = $argSettings['type'][0];
            $typeFieldType = $argSettings['type'][1];
            
            switch($typeClass) {
                case 'JsonData':
                  $type = Type::string();
                  break;
                  
                case 'Timestamp':
                  $type = Timestamp::$typeFieldType();
                  break;
                
                default:
                    $type = Type::$typeFieldType();
                    break;
            }
            
            $args[$argName] = [
                'type' => $type,
                'name' => $argName
            ];
        }

        return $args;
    }
}