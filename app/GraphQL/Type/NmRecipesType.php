<?php
namespace App\GraphQL\Type;

class NmRecipesType extends NmNodesType {
    protected $modelName = 'NmRecipe';
    
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