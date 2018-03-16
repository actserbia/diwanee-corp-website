<?php

namespace App\GraphQL\Type;

use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserInputsType extends GraphQLType
{
    protected $attributes = [
        'name' => 'UserInput',
        'description' => 'A type',
        'model' => User::class, // define model for users type
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::int(),
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
            ]
        ];
    }

    protected function resolveEmailField($root, $args)
    {
        return strtolower($root->email);
    }
}