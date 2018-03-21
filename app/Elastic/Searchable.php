<?php

namespace App\Elastic;


trait Searchable
{
    public static function bootSearchable()
    {
        if (config('services.elasticsearch.enabled')) {
            static::observe(ElasticsearchObserver::class);
        }
    }

    public function getSearchIndex()
    {
        return $this->getTable();
    }

    public function getSearchType()
    {
        if (property_exists($this, 'useSearchType')) {
            return $this->useSearchType;
        }
        return $this->getTable();
    }

    public function toSearchArray()
    {
        $this->populateAttributesFieldsData();
        $model = $this->load('model_type', 'author', 'tags');

        return $model->toArray();
    }
}