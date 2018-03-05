<?php
namespace App\GraphQL\Query;

use App\Node;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class NodesQuery extends Query {
    protected $name = 'Node';
    protected $nodeTypeId = null;
    
    protected $args = [];
    
    public function __construct($attributes = array()) {
        parent::__construct($attributes);
        
        $this->attributes['name'] = $this->name . 'sQuery';
        $this->attributes['description'] = 'A query';
    }

    public function type() {
        return Type::listOf(GraphQL::type($this->name));
    }

    public function args() {
        $args = [
            'id' => [
                'type' => Type::int(),
                'name' => 'id'
            ],
            'node_type_id' => [
                'type' => Type::int(),
                'name' => 'node_type_id'
            ],
            'created_at' => [
                'type' => Type::string(),
                'name' => 'created_at'
            ]
        ];
        
        foreach($this->args as $argName => $argType) {
            $args[$argName] = [
                'type' => Type::$argType(),
                'name' => $argName
            ];
        }
        
        return $args;
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info) {
        $where = function ($query) use ($args) {
            foreach($args as $key=>$arg) {
                $query->where($key, $arg);
            }
        };
        
        $items = Node::with(array_keys($fields->getRelations()))->where($where)
            ->select(array_merge($fields->getSelect()))
            ->paginate();
        
        return $items;
    }
}