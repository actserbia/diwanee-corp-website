<?php

namespace App\GraphQL\Query;

use App\Element;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Type\Scalar\Timestamp;


class ElementsQuery extends Query
{
    protected $attributes = [
        'name' => 'ElementsQuery',
        'description' => 'A query'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Element'));
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::int(),
                'name' => 'id'
            ],
            'data' => [
                'type' => Type::string(),
                'name' => 'data'
            ],
            'type' => [
                'type' => Type::string(),
                'name' => 'type'
            ],
            'created_at' => [
                'type' => Timestamp::type(),
                'name' => 'created_at'
            ]
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {

        $where = function ($query) use ($args) {
            foreach($args as $key=>$arg) {
                $query->where($key, $arg);
            }
        };

        $elements = Element::with(array_keys($fields->getRelations()))
            ->where($where)
            ->select($fields->getSelect())
            ->paginate();
        return $elements;

    }
}