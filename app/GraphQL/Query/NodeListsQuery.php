<?php

namespace App\GraphQL\Query;
use App\NodeList;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;

class NodeListsQuery extends AppQuery {
    protected $modelName = 'App\\NodeList';
    
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
                'type' => Type::listOf(Type::int())
            ],
            'name' => [
                'name' => 'name',
                'type' => Type::string()
            ],
            'created_at' => [
                'type' => Type::listOf(Type::string()),
                'name' => 'created_at',
                'category' => 'date'
            ]
        ];
    }
    
    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info) {
        $relations = array_keys($fields->getRelations());
        
        if(in_array('list_items', $relations)) {
            foreach($relations as $index => $relation) {
                if($relation === 'list_items') {
                    unset($relations[$index]);
                }
            }
            
            $query = NodeList::with($relations)
                ->where($this->makeWhereQuery($args));
            $this->addOrderByIdToQuery($query, $args);
            $lists = $query->paginate();
            return $lists;
        } else {
            return parent::resolve($root, $args, $fields, $info);
        }
    }
}