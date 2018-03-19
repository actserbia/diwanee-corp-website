<?php
namespace App\GraphQL\Query;

use Illuminate\Support\Str;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Type\Scalar\Timestamp;

class NmNodesQuery extends AppQuery {
    protected $modelName = '';

    protected $args = [];

    public function __construct($attributes = array()) {
        parent::__construct($attributes);

        $this->attributes['name'] = Str::plural($this->name) . 'Query';
        $this->attributes['description'] = 'A query';
    }

    public function type() {
        return Type::listOf(GraphQL::type($this->name));
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
            $args[$argName] = [
                'type' => ($argSettings['type'][0] === 'RawData') ? Type::string() : $argSettings['type'][0]::$argSettings['type'][1],
                'name' => $argName
            ];
        }

        return $args;
    }
}