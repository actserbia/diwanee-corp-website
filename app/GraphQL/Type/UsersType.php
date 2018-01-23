<?php

namespace App\GraphQL\Type;

use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UsersType extends GraphQLType
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

            // Nested Resource
//            'nodes' => [
//                'args' => [
//                    'id' => [
//                        'type' => Type::int(),
//                        'description' => 'id of the node',
//                    ],
//                    'title' => [
//                        'type' => Type::string(),
//                        'description' => 'title'
//                    ],
//                ],
//                'type' => Type::listOf(GraphQL::type('Node')),
//                'description' => 'node',
//            ],
        ];
    }

    protected function resolveEmailField($root, $args)
    {
        return strtolower($root->email);
    }
}