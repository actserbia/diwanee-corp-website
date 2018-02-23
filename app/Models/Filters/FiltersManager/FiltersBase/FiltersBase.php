<?php
namespace App\Models\Filters\FiltersManager\FiltersBase;

abstract class FiltersBase {
    protected $fieldModel = null;

    public function __construct($fieldSettings) {
        foreach($fieldSettings as $settingName => $fieldSetting) {
            $this->$settingName = $fieldSetting;
        }
        
        if(isset($this->relation)) {
            $this->fieldModel = $this->model->getRelationModel($this->relation);
        } else {
            $this->fieldModel = $this->model;
        }
    }
}