<?php
namespace App\GraphQL\Query;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\StringType;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;

class AppQuery extends Query {
    public function resolve($root, $args, SelectFields $fields,  ResolveInfo $info) {
        $items = null;
        
        if($this->modelName !== '') {
            $query = $this->modelName::with(array_keys($fields->getRelations()))
                ->where($this->makeWhereQuery($args));
            $this->addOrderByIdToQuery($query, $args);
            $items = $query->select($fields->getSelect())->paginate(); //$args['limit'], ['*'], 'page', $args['page']);
        }
        
        return $items;
    }
    
    public function makeWhereQuery($args) {
        $where = function ($query) use ($args) {
            foreach($args as $argName => $argValue) {
                $this->addArgFilterToQuery($query, $argName, $argValue);
            }
        };
        
        return $where;
    }
    
    private function addArgFilterToQuery($query, $argName, $argValue) {
        $queryArg = $this->args()[$argName];
        if($queryArg['type'] instanceof ListOfType) {
            if(isset($queryArg['category']) && $queryArg['category'] === 'date') {
                $this->addDateArgFilterToQuery($query, $argName, $argValue);
            } elseif($queryArg['type']->ofType->name === 'String') {
                $this->addStringListArgFilterToQuery($query, $argName, $argValue);
            } else {
                $query->whereIn($argName, $argValue);
            }
        } elseif($queryArg['type'] instanceof StringType) {
            $query->where($argName, 'like', $argValue);
        } else {
            $query->where($argName, $argValue);
        }
    }
    
    private function addStringListArgFilterToQuery($query, $argName, $argValues) {
        foreach($argValues as $argValue) {
            $query->orWhere($argName, 'like', $argValue);
        }
    }
    
    private function addDateArgFilterToQuery($query, $argName, $argValues) {
        $operators = ['>=', '<=', '>', '<', '='];
        if(in_array($argValues[0], $operators)) {
            $operator = $argValues[0];
            $query->where($argName, $operator, $argValues[1]);
        } else {
            $query->where($argName, '>=', $argValues[0])->where($argName, '<=', $argValues[1]);
        }
    }
    
    public function addOrderByIdToQuery($query, $args) {
        if(isset($args['id'])) {
            $query->orderByRaw('FIELD(`' . 'id' . '`, "' . implode('","', $args['id']) . '")');
        }
        
        return $query;
    }
}