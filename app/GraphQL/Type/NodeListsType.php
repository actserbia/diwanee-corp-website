<?php

namespace App\GraphQL\Type;

use App\NodeList;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use App\GraphQL\Type\Scalar\Timestamp;

class NodeListsType extends GraphQLType
{
    protected $attributes = [
        'name' => 'NodeLists',
        'description' => 'Node Lists',
        'model' => NodeList::class
    ];

    // define field of type
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Name'
            ],
            'filter_tags' => [
                'type' => Type::listOf(GraphQL::type('Tag')),
                'description' => 'Filter Tags'
            ],
            'filter_authors' => [
                'type' => Type::listOf(GraphQL::type('User')),
                'description' => 'Filter Authors'
            ],
            'author' => [
                'type' => GraphQL::type('User'),
                'description' => 'Author'
            ],
            'limit' => [
                'type' => Type::int(),
                'description' => 'Limit'
            ],
            'order' => [
                'type' => Type::string(),
                'description' => 'Order'
            ],
            'created_at' => [
                'type' => Timestamp::type(),
                'name' => 'created_at'
            ],

            'list_items' => [
                'type' => Type::listOf(GraphQL::type('Node')),
                'description' => 'List Items'
            ],
        ];
    }

    protected function resolveOrderField($root, $args) {
        return ($root->order === 1) ? 'Asc' : 'Desc';
    }
}