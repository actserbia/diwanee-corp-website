<?php
namespace App\Models\Filters\FiltersManager;

use App\Constants\Models;
use App\Constants\Filters;
use App\Models\ModelsUtils;

trait AttributesJson {
    private function jsonField() {
        return isset($this->json_attribute['settings']['field']) ? $this->json_attribute['settings']['field'] : $this->json_attribute['name'];
    }
    
    protected function queryWithAttribute($query, $param) {
        ModelsUtils::addQueryJsonFieldFilters($query, $this->json_attribute['settings']);

        $operator = $this->searchTypeNegation ? 'not like' : 'like';
        $template = str_replace('[PARAM]', $param, $this->getSearchTemplate());
        $dataFilter = '"' . $this->jsonField() . '":"' . $template . '"';

        $query->where($this->getAttribute(), $operator, '%' . $dataFilter . '%');

        if($this->searchType == Filters::SearchLike) {
            $dataFilter = '"' . $this->jsonField() . '":"%"' . $template . '"%"';
            $query->where($this->getAttribute(), 'not like', '%' . $dataFilter . '%');
        }

        $this->addTrashed($query);
    }

    public function getSearchTypesForDropdown() {
        $type = $this->fieldModel->attributeType($this->getAttribute() . ':' . $this->jsonField());
        return Filters::getSearchTypesForDropdown($type === Models::AttributeType_Number ? Models::AttributeType_Number : Models::AttributeType_Text);
    }

    public function replaceWithAttributtesNames($text) {
        if($this->fieldModel->attributeType($this->getAttribute() . ':' . $this->jsonField()) === Models::AttributeType_Date) {
            $text = str_replace(['.0', '.1'], [' ' . __('models_labels.global.start_date'), ' ' . __('models_labels.global.end_date')], $text);
        }

        return $text;
    }
}