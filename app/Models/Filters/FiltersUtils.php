<?php
namespace App\Models\Filters;

use App\Utils\Utils;
use Request;

class FiltersUtils {
    public static function prepareParams($params) {
        array_walk($params, function(&$param, $paramName) {
            if((strpos($paramName, 'connectionType') === FALSE && !in_array($paramName, array('perPage', 'page')) && !is_array($param))) {
                $param = explode(',', $param);
            }
            if(is_array($param)) {
                $param = Utils::removeEmptyValues($param);
            }
        });

        return $params;
    }
    
    public static function getLinkCollapseData($blockName) {
        $expandedBlocks = self::formValue('expandedBlocks');
        
        if(in_array($blockName, $expandedBlocks)) {
            return 'data-toggle="collapse" data-target="#' . $blockName . '" class=""';
        } else {
            return 'data-toggle="collapse" data-target="#' . $blockName . '" class="collapsed"';
        }
    }
    
    public static function getBlockCollapseData($blockName) {
        $expandedBlocks = self::formValue('expandedBlocks');
        
        if(in_array($blockName, $expandedBlocks)) {
            return 'id="' . $blockName . '" class="collapse in" aria-expanded="true" style=""';
        } else {
            return 'id="' . $blockName . '" class="collapse" aria-expanded="false" style="height: 0px;"';
        }
    }

    public static function checkIfIsSetFormValue($paramName) {
        $isSet = false;

        if(Request::post($paramName) !== null) {
            $isSet = true;
        }

        if(Request::old($paramName) !== null) {
            $isSet = true;
        }

        return $isSet;
    }


    public static function formValue($paramName, $index = null) {
        $value = [];

        if(Request::post($paramName) !== null) {
            $value = Request::post($paramName);
        }

        if(Request::old($paramName) !== null) {
            $value = Request::old($paramName);
        }

        if(($index !== null)) {
            $value = isset($value[$index]) ? $value[$index] : '';
        }

        return $value;
    }

    public static function checkFormSelectValue($paramName, $itemValue, $defaultValue = '') {
        if(Request::post($paramName) !== null) {
            return static::checkFormValue(Request::post($paramName), $itemValue);
        }

        if(Request::old($paramName) !== null) {
            return static::checkFormValue(Request::old($paramName), $itemValue);
        }

        return $itemValue === $defaultValue;
    }

    public static function checkFormSelectValueByIndex($paramName, $itemValue, $index, $defaultValue = '') {
        if(Request::post($paramName)[$index] !== null) {
            return static::checkFormValue(Request::post($paramName)[$index], $itemValue);
        }

        if(Request::old($paramName)[$index] !== null) {
            return static::checkFormValue(Request::old($paramName)[$index], $itemValue);
        }

        return $itemValue === $defaultValue;
    }

    private static function checkFormValue($formValue, $itemValue) {
        return is_array($formValue) ? in_array($itemValue, $formValue) : ($itemValue == $formValue);
    }
}