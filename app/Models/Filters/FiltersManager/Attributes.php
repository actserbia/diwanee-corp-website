<?php
namespace App\Models\Filters\FiltersManager;

use App\Constants\Models;
use App\Constants\Filters;

trait Attributes {
    private function queryWithAttribute($query, $param) {
        $isRelation = isset($this->relation);

        $options = $this->getSearchTypesOptions($isRelation);

        $functionNull = $options['nullFunction'];
        $functionConnect = $options['connectFunction'];
        $template = str_replace('[PARAM]', $param, $options['template']);

        $query->$functionNull($this->getAttribute())->$functionConnect($this->getAttribute(), $options['operator'], $template);

        $this->addTrashed($query);
    }

    protected function queryWithAttributeAggregate($query, $param) {
        $isRelation = isset($this->relation);

        $options = $this->getSearchTypesOptions($isRelation);

        $functionAggregate = $this->getConnectionTypeFunction('functionAggregate');
        $template = str_replace('[PARAM]', $param, $options['template']);

        $this->addAggregation($query);
        $query->$functionAggregate($this->getAttribute(), $options['operator'], $template);

        $this->addTrashed($query);
    }

    private function addAggregation($query) {
        $attributes = preg_split('/(_)/', $this->getAttribute());
        $aggregationFunctionName = 'with' . ucfirst($attributes[1]);
        if(method_exists($query, $aggregationFunctionName)) {
            $query->$aggregationFunctionName($attributes[0]);
        }
    }

    public function getSearchTypesForDropdown() {
        $type = $this->fieldModel->attributeType($this->getAttribute());
        return Filters::getSearchTypesForDropdown($type === Models::AttributeType_Number ? Models::AttributeType_Number : Models::AttributeType_Text);
    }

    public function replaceWithAttributtesNames($text) {
        if($this->fieldModel->attributeType($this->getAttribute()) === Models::AttributeType_Date) {
            $text = str_replace(['.0', '.1'], [' ' . __('models_labels.global.start_date'), ' ' . __('models_labels.global.end_date')], $text);
        }
        return $text;
    }
}