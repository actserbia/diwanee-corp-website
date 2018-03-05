<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Type\Scalar\Timestamp;


class ElementsQuery extends AppQuery {
    protected $modelName = 'App\\Element';
    
    protected $attributes = [
        'name' => 'ElementsQuery',
        'description' => 'A query'
    ];

    public function type() {
        return Type::listOf(GraphQL::type('Element'));
    }

    public function args() {
        return [
            'id' => [
                'type' => Type::int(),
                'name' => 'id'
            ],
            'data' => [
                'type' => Type::string(),
                'name' => 'data'
            ],
            'type' => [
                'type' => Type::string(),
                'name' => 'type'
            ],
            'created_at' => [
                'type' => Timestamp::type(),
                'name' => 'created_at'
            ]
        ];
    }
}