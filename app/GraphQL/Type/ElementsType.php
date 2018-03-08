<?php

namespace App\GraphQL\Type;

use App\Element;
use App\GraphQL\Type\Scalar\Timestamp;
use App\GraphQL\Type\Scalar\Data;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ElementsType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Elements',
        'description' => 'Elements type',
        'model' => Element::class
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the tag'
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'type'
            ],
            'created_at' => [
                'type' => Timestamp::type(),
                'description' => 'created'
            ],
            'data' => [
                'type' => Data::type(),
                'description' => 'Element data'
            ],
            'element_item_node' => [
                'type' => Type::listOf(GraphQL::type('Node')),
                'description' => 'Element item node'
            ],
            'element_item_list' => [
                'type' => Type::listOf(GraphQL::type('NodeList')),
                'description' => 'Element item list'
            ]
        ];
    }
}