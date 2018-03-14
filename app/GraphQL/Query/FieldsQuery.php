<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;


class FieldsQuery extends AppQuery {
    protected $modelName = 'App\\Field';

    protected $attributes = [
        'name' => 'FieldsQuery',
        'description' => 'A fields query'
    ];

    public function type() {
        return Type::listOf(GraphQL::type('Field'));
    }

    public function args() {
        return [
            'id' => [
                'type' => Type::listOf(Type::int()),
                'name' => 'id'
            ],
            'title' => [
                'type' => Type::string(),
                'name' => 'title'
            ]

        ];
    }
}