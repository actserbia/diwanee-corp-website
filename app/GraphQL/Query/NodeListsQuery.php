<?php

namespace App\GraphQL\Query;
use App\NodeList;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class NodeListsQuery extends Query
{
    protected $attributes = [
        'name' => 'Node Lists Query',
        'description' => 'Node Lists Query'
    ];

    public function type() {
        return Type::listOf(GraphQL::type('NodeList'));
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int()
            ]
        ];
    }
    public function resolve($root, $args, SelectFields $fields)
    {
        $where = function ($query) use ($args) {
            foreach($args as $key=>$arg) {
                $query->where($key, $arg);
            }
        };
        
        $lists = NodeList::with(array_keys($fields->getRelations()))
            ->where($where)
            ->select($fields->getSelect())
            ->paginate();
        return $lists;
    }
}