<?php
namespace App\Models\ModelManager;

use App\Constants\Models;
use App\Utils\Utils;

abstract class ModelManager {
    protected $fieldModel = null;

    private $confirmation = false;

    public function __construct($fieldSettings) {
        foreach($fieldSettings as $settingName => $fieldSetting) {
            $this->$settingName = $fieldSetting;
        }

        if(strpos($this->attribute, '_confirmation') !== false) {
            $this->attribute = str_replace('_confirmation', '', $this->attribute);
            $this->confirmation = true;
        }

        if(isset($this->relation)) {
            $this->fieldModel = $this->model->getRelationModel($this->relation);
        } else {
            $this->fieldModel = $this->model;
        }
    }

    public function fieldLabel() {
        $label = '';
        
        if($this->confirmation) {
            $label .= __('models_labels.global.confirm') . ' ';
        }
        
        if(isset($this->relation)) {
            $label .= Utils::translate('models_labels.' . $this->model->modelName . '.' . $this->relation . '_label', $this->relation) . ' ';
        }
        
        $label .=  $this->getTranslatedLabel('models_labels.' . $this->fieldModel->modelName . '.' . $this->attributeTranslationName());
        
        return $label;
    }

    private function getTranslatedLabel($label) {
        $translatedLabel = __($label);
        if(is_array($translatedLabel)) {
            $translatedLabel = __($label . '_label');
        } else {
            $translatedLabel = Utils::translate($label, $this->attribute);
        }

        return $translatedLabel;
    }

    public function getEnumListForDropdown() {
        return __('constants.' . $this->fieldModel->modelName . Utils::getFormattedName($this->attributeName('_')));
    }

    public function fieldValue($value = null) {
        $fieldValues = $this->getEnumListForDropdown();

        if($value === null) {
            $attribute = $this->attribute;
            $value = $this->fieldModel->$attribute;
        }

        return is_array($fieldValues) ? $fieldValues[$value] : $value;
    }

    public function attributeType() {
        return $this->fieldModel->attributeType($this->attributeName(':'));
    }

    public function formFieldType() {
        return Models::FormFieldTypesList[$this->attributeType()];
    }

    abstract public function getTypeaheadItems();
}