<?php

namespace App\GraphQL\Type;

use App\FieldType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FieldTypeType extends GraphQLType
{
    protected $attributes = [
        'name' => 'FieldTypes',
        'description' => 'Field types',
        'model' => FieldType::class
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'title'
            ],
            'tags' => [
                'type' => Type::listOf(GraphQL::type('Tag')),
                'description' => 'tags'
            ],
            'fields' => [
                'type' => Type::listOf(GraphQL::type('Field')),
                'description' => 'fields'
            ]
        ];
    }
}