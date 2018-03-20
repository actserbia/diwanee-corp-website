<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;


class FieldTypeQuery extends AppQuery {
    protected $modelName = 'App\\FieldType';

    protected $attributes = [
        'name' => 'FieldTypesQuery',
        'description' => 'A query'
    ];

    public function type() {
        return Type::listOf(GraphQL::type('FieldType'));
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
            ]
        ];
    }
}