<?php
namespace App\GraphQL\Type;

use App\Node;
use App\NodeType;
use App\Utils\Utils;
use App\Constants\Settings;
use App\GraphQL\Type\Scalar\Timestamp;
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
            ],
            'tags' => [
                'type' => Type::listOf(GraphQL::type('Tag')),
                'description' => 'tags'
            ],
            'elements' => [
                'type' => Type::listOf(GraphQL::type('Element')),
                'description' => 'Elements'
            ],
            'created_at' => [
                'type' => Timestamp::type(),
                'name' => 'created_at'
            ]
        ];
        
        $nodeTypeNames = NodeType::pluck('name');
        foreach($nodeTypeNames as $nodeTypeName) {
            $fields['additional_fields_from_' . Utils::getFormattedDBName($nodeTypeName)] = [
                'type' => GraphQL::type(ucfirst(Settings::NodeModelPrefix) . Utils::getFormattedName($nodeTypeName, ' ')),
                'description' => 'Dinamic generated data'
            ];
        }
        
        return $fields;
    }
}