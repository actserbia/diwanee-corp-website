<?php
namespace App\GraphQL\Type;

use App\Node;
use App\NodeType;
use App\Constants\Settings;
use App\Utils\Utils;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class NodesType extends GraphQLType {
    protected $attributes = [
        'name' => 'Nodes',
        'description' => 'Nodes type',
        'model' => Node::class,
    ];
    
    public function fields() {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id'
            ],
            'title' => [
                'type' => Type::string(),
                'description' => 'Title'
            ],
            'author' => [
                'type' => GraphQL::type('User'),
                'description' => 'Author'
            ]
        ];
        
        //$nodeTypeNames = NodeType::pluck('name');
        //foreach($nodeTypeNames as $nodeTypeName) {
        //    $fields['additional_fields_from_' . Utils::getFormattedDBName($nodeTypeName)] = [
        //        'type' => GraphQL::type(ucfirst(Settings::NodeModelPrefix) . Utils::getFormattedName($nodeTypeName)),
        //        'description' => 'Dinamic generated data'
        //    ];
        //}
        
        return $fields;
    }
}