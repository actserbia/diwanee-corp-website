<?php
namespace App\GraphQL\Query;

class NmRecipesQuery extends NodesNmQuery {
    protected $name = 'Recipe';

    protected $args = [
        'meta_title' => 'int'
    ];
}