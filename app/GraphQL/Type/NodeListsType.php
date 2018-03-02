<?php

namespace App\GraphQL\Type;

use App\NodeList;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class NodeListsType extends GraphQLType
{
    protected $attributes = [
        'name' => 'NodeLists',
        'description' => 'A type',
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
            'tags' => [
                'type' => Type::listOf(GraphQL::type('Tag')),
                'description' => 'Filter Tags'
            ],
            'authors' => [
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