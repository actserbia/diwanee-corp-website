<?php

namespace App\GraphQL\Type;

use App\Tag;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TagsType extends GraphQLType
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
//            'tag_type' => [
//                'type' => GraphQL::type('TagType'),
//                'description' => 'Type of tag'
//            ]
        ];
    }
}