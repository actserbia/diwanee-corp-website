<?php

namespace App\GraphQL\Query;

use App\Tag;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class TagsQuery extends Query
{
    protected $attributes = [
        'name' => 'TagsQuery',
        'description' => 'A query'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Tag'));
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::int(),
                'name' => 'id'
            ],
            'name' => [
                'type' => Type::string(),
                'name' => 'name'
            ],
            'type' => [
                'type' => Type::string(),
                'name' => 'type'
            ]
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();


        $where = function ($query) use ($args) {
            if (isset($args['id'])) {
                $query->where('id',$args['id']);
            }
        };
        $tags = Tag::with(array_keys($fields->getRelations()))
            ->where($where)
            ->select($fields->getSelect())
            ->paginate();
        return $tags;


    }
}