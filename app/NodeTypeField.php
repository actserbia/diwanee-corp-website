<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Constants\Models;

class NodeTypeField extends Pivot {

    public $pivotParent = 'App\\Field';
    protected $foreignKey = 'node_type_id';
    protected $relatedKey = 'field_id';

    public $timestamps = false;

    protected $casts = [
        'additional_settings' => 'array'
    ];

    protected static $pivotFields = ['active', 'required', 'additional_settings'];

    protected static $additionalSettingsFields = ['multiple', 'render_type'];

    public function getMultipleAttribute() {
        return isset($this->additional_settings['multiple']) ? $this->additional_settings['multiple'] : null;
    }

    public function getRenderTypeAttribute() {
        return isset($this->additional_settings['render_type']) ? $this->additional_settings['render_type'] : null;
    }
    
    public function getFormHierarchyAttribute() {
        return isset($this->additional_settings['multiple']) ? $this->additional_settings['multiple']['hierarchy'] : null;
    }

    public static function getPivotFields() {
        return static::$pivotFields;
    }

    public static function populatePivotData($data) {
        foreach($data as $key => $pivotData) {
            foreach($pivotData as $pivotAttribute => $pivotAttributeValue) {
                if(in_array($pivotAttribute, static::$additionalSettingsFields)) {
                    $data[$key]['additional_settings'][$pivotAttribute] = $pivotAttributeValue;
                    unset($data[$key][$pivotAttribute]);
                }
            }
        }

        return $data;
    }
}