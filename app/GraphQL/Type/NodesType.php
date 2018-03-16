<?php
namespace App\GraphQL\Type;

use Illuminate\Support\Str;
use App\Node;
use App\NodeType;
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
                'args' => [
                    'id' => [
                        'type' => Type::int(),
                        'description' => 'id of the article',
                    ],
                    'name' => [
                        'type' => Type::string(),
                        'description' => 'title'
                    ],
                    'tag_type_id' => [
                        'type' => Type::int(),
                        'description' => 'taxonomy id'
                    ],
                    'taxonomy' => [
                        'type' => Type::string(),
                        'description' => 'taxonomy name'
                    ]
                    ],
                'type' => Type::listOf(GraphQL::type('Tag')),
                'description' => 'tags'
            ],
            'elements' => [
                'type' => Type::listOf(GraphQL::type('Element')),
                'description' => 'Elements',
                'args' => [
                    'type' => [
                        'type' => Type::string(),
                        'description' => 'type of element',
                    ]
                 ]
            ],
            'created_at' => [
                'type' => Timestamp::type(),
                'name' => 'created_at'
            ]
        ];
        
        $nodeTypeNames = NodeType::pluck('name');
        foreach($nodeTypeNames as $nodeTypeName) {
            $fields['additional_fields_from_' . Str::snake($nodeTypeName)] = [
                'type' => GraphQL::type(ucfirst(Settings::NodeModelPrefix) . Str::studly($nodeTypeName)),
                'description' => 'Dinamic generated data'
            ];
        }
        
        return $fields;
    }

    public function resolveTagsField($root, $args)
    {
        if (isset($args['id'])) {
            return  $root->tags->where('id', $args['id']);
        }
        if (isset($args['tag_type_id'])) {
            return  $root->tags->where('tag_type_id', $args['tag_type_id']);
        }
        if (isset($args['taxonomy'])) {
            return  $root->tags->where('tag_type.name', $args['taxonomy']);
        }

        return $root->tags;
    }

    public function resolveElementsField($root, $args)
    {

        if (isset($args['type'])) {
            return  $root->elements->where('type', $args['type']);
        }

        return $root->elements;
    }
}