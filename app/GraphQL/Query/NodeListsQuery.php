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
            ],
            'created_at' => [
                'type' => Type::string(),
                'name' => 'created_at'
            ]
        ];
    }
    public function resolve($root, $args, SelectFields $fields) {
        $where = function ($query) use ($args) {
            foreach($args as $key => $arg) {
                $query->where($key, $arg);
            }
        };
        
        $relations = array_keys($fields->getRelations());
        if(in_array('list_items', $relations)) {
            $lists = NodeList::with($relations)
                ->where($where)
                ->paginate();

            foreach($lists as $list) {
                $list->list_items = $list->items;
            }
        } else {
            $lists = NodeList::with($relations)
                ->where($where)
                ->select($fields->getSelect())
                ->paginate();
        }

        return $lists;
    }
}