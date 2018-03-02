<?php
namespace App\GraphQL\Type;

class NmRecipesType extends NodesNmType {
    protected $modelName = 'NmRecipe';

    protected $fields = [
        'meta_title' => [
            'type' => 'string'
        ]
    ];
}