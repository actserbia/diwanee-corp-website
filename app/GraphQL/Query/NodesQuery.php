<?php
namespace App\GraphQL\Query;

use Illuminate\Support\Str;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;

use App\NodeType;
use App\User as Author;

class NodesQuery extends AppQuery {
    protected $modelName = 'App\\Node';
    
    protected $args = [];
    
    public function __construct($attributes = array()) {
        parent::__construct($attributes);
        
        $this->attributes['name'] = Str::plural($this->name) . 'Query';
        $this->attributes['description'] = 'A query';
    }

    public function type() {
        return GraphQL::paginate('Node');
        //return Type::listOf(GraphQL::type('Node'));
    }

    public function args() {
        $args = [
            'id' => [
                'name' => 'id',
                'type' => Type::listOf(Type::int())
            ],
            'node_type_id' => [
                'type' => Type::listOf(Type::int()),
                'name' => 'node_type_id'
            ],
            'node_type' => [
                'type' => Type::listOf(Type::string()),
                'name' => 'node_type'
            ],
            'created_at' => [
                'type' => Type::listOf(Type::string()),
                'name' => 'created_at',
                'category' => 'date'
            ],
            'title' => [
                'type' => Type::listOf(Type::string()),
                'name' => 'title'
            ],
            'author' => [
                'type' => GraphQL::type('UserInput'),
                'name' => 'author'
            ],
            'author_id' => [
                'type' => Type::listOf(Type::int()),
                'name' => 'author_id'
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

        if(array_key_exists('node_type', $args)) {
            $nodeType = NodeType::where('name', $args['node_type'])->first();
            $args['node_type_id'] = [$nodeType->id];
            unset($args['node_type']);
        }

        if(array_key_exists('author', $args)) {
            $authors = Author::where($args['author'])->select('id')->get();
            $args['author_id'] = $authors;
            unset($args['author']);
        }

        return parent::resolve($root, $args, $fields, $info);
    }
}