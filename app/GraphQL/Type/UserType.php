<?php

namespace App\GraphQL\Type;

use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Users',
        'description' => 'A type',
        'model' => User::class, // define model for users type
    ];

    // define field of type
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the user'
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of user'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the user'
            ],
            'role' => [
                'type' => Type::string(),
                'description' => 'The role of the user'
            ],
            'active' => [
                'type' => Type::int(),
                'description' => 'User status'
            ],

            // Nested Resource
            'nodes' => [
                'args' => [
                    'id' => [
                        'name' => 'id',
                        'type' => Type::int()

                    ],
                    'title' => [
                        'name' => 'title',
                        'type' => Type::string()
                    ],
                    'tag' => [
                        'name' => 'tag',
                        'type' => Type::string()
                    ],
                ],
                'type' => Type::listOf(GraphQL::type('Node')),
                'description' => 'User nodes'
            ]
        ];
    }

    protected function resolveEmailField($root, $args)
    {
        return strtolower($root->email);
    }

    public function resolveNodesField($root, $args)
    {
        if (isset($args['id'])) {
            return  $root->nodes->where('id', $args['id']);
        }
        if (isset($args['title'])) {
            return  $root->nodes->where('title', 'like', $args['title']);
        }
        if (isset($args['tag'])) {
            return  $root->nodes->where('tag.name', $args['tag']);
        }

        return $root->nodes;
    }

}