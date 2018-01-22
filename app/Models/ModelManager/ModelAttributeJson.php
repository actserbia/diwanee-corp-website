<?php
namespace App\Models\ModelManager;

use App\Models\ModelsUtils;

class ModelAttributeJson extends ModelManager {
    private function jsonField() {
        return isset($this->json_attribute['settings']['field']) ? $this->json_attribute['settings']['field'] : $this->json_attribute['name'];
    }
    
    protected function attributeName($delimiter) {
        return $this->attribute . $delimiter . $this->jsonField();
    }
    
    protected function attributeTranslationName() {
        return $this->attribute . '.' . $this->json_attribute['name'];
    }
    
    public function getTypeaheadItems() {
        $field = $this->attribute;
        $jsonField = $this->jsonField();

        $items = $this->fieldModel::select(DB::raw('SUBSTRING(' . $field . ', LOCATE(\'"' . $jsonField . '":"\', ' . $field . ') + CHAR_LENGTH(\'"' . $jsonField . '":"\'), LOCATE(\'"\', ' . $field . ', LOCATE(\'"' . $jsonField . '":"\', ' . $field . ') + CHAR_LENGTH(\'"' . $jsonField . '":"\') ) - (LOCATE(\'"' . $jsonField . '":"\', ' . $field . ') + CHAR_LENGTH(\'"' . $jsonField . '":"\')) ) AS value'))
            ->distinct()
            ->where(DB::raw('LOCATE(\'"' . $jsonField . '":"\', ' . $field . ')'), '>', '0');

        ModelsUtils::addQueryJsonFieldFilters($items, $this->json_attribute['settings']);

        if(isset($this->relation)) {
            ModelsUtils::addQueryRelationFilters($items, $this->model, $this->relation);
        }

        return $items->pluck('value');
    }
}