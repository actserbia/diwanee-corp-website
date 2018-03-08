<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;


class TagsQuery extends AppQuery {
    protected $modelName = 'App\\Tag';
    
    protected $attributes = [
        'name' => 'TagsQuery',
        'description' => 'A query'
    ];

    public function type() {
        return Type::listOf(GraphQL::type('Tag'));
    }

    public function args() {
        return [
            'id' => [
                'type' => Type::listOf(Type::int()),
                'name' => 'id'
            ],
            'name' => [
                'type' => Type::string(),
                'name' => 'name'
            ],
            'type' => [
                'type' => Type::string(),
                'name' => 'type'
            ],
            'created_at' => [
                'type' => Type::listOf(Type::string()),
                'name' => 'created_at',
                'category' => 'date'
            ]
        ];
    }
}