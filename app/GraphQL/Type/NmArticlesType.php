<?php
namespace App\GraphQL\Type;

class NmArticlesType extends NmNodesType {
    protected $modelName = 'NmArticle';
    
    protected $fields = [
        'meta_title' => [
            'type' => 'string',
            'required' => false,
            'description' => 'Meta Title'
        ],
        'meta_description' => [
            'type' => 'string',
            'required' => false,
            'description' => 'Meta Description'
        ]
    ];
}