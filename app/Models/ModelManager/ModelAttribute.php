<?php
namespace App\Models\ModelManager;

use App\Models\ModelsUtils;

class ModelAttribute extends ModelManager {
    protected function attributeName() {
        return $this->attribute;
    }
    
    protected function attributeTranslationName() {
        return $this->attribute;
    }
    
    public function getTypeaheadItems() {
        $field = $this->attribute;

        $items = $this->fieldModel::select($field . ' AS value')
            ->distinct()
            ->whereNotNull($field);

        if(isset($this->relation)) {
            ModelsUtils::addQueryRelationFilters($items, $this->model, $this->relation);
        }

        return $items->pluck('value');
    }
}