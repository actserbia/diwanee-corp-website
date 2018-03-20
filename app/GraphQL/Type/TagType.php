<?php

namespace App\GraphQL\Type;

use App\Tag;
use App\GraphQL\Type\Scalar\Timestamp;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TagType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Tags',
        'description' => 'Tags type',
        'model' => Tag::class
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
            ],
            'created_at' => [
                'type' => Timestamp::type(),
                'description' => 'created'
            ],
            'nodes' => [
                'type' => Type::listOf(GraphQL::type('Node')),
                'description' => 'Nodes'
            ],
            'tag_type' => [
                'type' => GraphQL::type('FieldType'),
                'description' => 'Type of tag'
            ]
        ];
    }
}