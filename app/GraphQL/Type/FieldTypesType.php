<?php

namespace App\GraphQL\Type;

use App\FieldType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FieldTypesType extends GraphQLType
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
                'description' => 'The id of the tag'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'title'
            ]
        ];
    }
}