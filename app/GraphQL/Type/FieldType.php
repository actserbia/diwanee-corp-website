<?php

namespace App\GraphQL\Type;

use App\Field;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FieldType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Fields',
        'description' => 'Fields',
        'model' => Field::class
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the tag'
            ],
            'title' => [
                'type' => Type::string(),
                'description' => 'title'
            ],
            'field_types' => [
                'type' => Type::listOf(GraphQL::type('FieldType')),
                'description' => 'field types'
            ]
        ];
    }
}