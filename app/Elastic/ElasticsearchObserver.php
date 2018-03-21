<?php

namespace App\Elastic;

use Elasticsearch\Client;

class ElasticsearchObserver
{
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function savedWithRelations($model)
    {
        $this->elasticsearch->index([
            'index' => env('ELASTICSEARCH_INDEX_PREFIX').'_'.$model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'id' => $model->id,
            'body' => $model->toSearchArray(),
        ]);
    }

    public function deleted($model)
    {
        $this->elasticsearch->delete([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'id' => $model->id,
        ]);
    }
}