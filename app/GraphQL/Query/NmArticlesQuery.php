<?php
namespace App\GraphQL\Query;

class NmArticlesQuery extends NodesNmQuery {
    protected $name = 'Article';

    protected $args = [
        'meta_title' => 'string'
    ];
}