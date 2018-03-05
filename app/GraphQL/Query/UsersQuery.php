<?php

namespace App\GraphQL\Query;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UsersQuery extends AppQuery {
    protected $modelName = 'App\\User';
    
    protected $attributes = [
        'name' => 'Users Query',
        'description' => 'A query of users'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('User'));
        // result of query with pagination laravel
        //return GraphQL::paginate('user');
    }

    // arguments to filter query
    // example: /graphql/query/user?query=query+FetchUsers{users(id:1){name,role,articles{id}}}
    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int()
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::string()
            ],
            'role' => [
                'name' => 'role',
                'type' => Type::string()
            ]
        ];
    }
}