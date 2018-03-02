<?php
namespace App\GraphQL\Type;

class NmArticlesType extends NodesNmType {
    protected $modelName = 'NmArticle';

    protected $fields = [
        'meta_title' => [
            'type' => 'string'
        ]
    ];
}