<?php
namespace App\Utils;

class Utils {
    private static $autoincrement = [];
    public static $modelType;

    public static function getForDropdown($items, $prefix = '') {
        $dropdownList = array();

        foreach($items as $item) {
            $dropdownList[$item] = empty($prefix) ? $item : __($prefix . '.' . $item);
        }

        return $dropdownList;
    }
    
    public static function getFormattedName($name, $delimiter = '_') {
        return str_replace($delimiter, '', ucwords($name, $delimiter));
    }
    
    public static function getFormattedDBName($name, $delimiter = '_') {
        return str_replace(' ', $delimiter, strtolower($name));
    }

    public static function removeEmptyValues($list) {
        return array_filter($list, function ($item) {
            return $item !== '';
        });
    }

    public static function autoincrement($type, $autoincrement = true) {
        if(!isset(self::$autoincrement[$type])) {
            self::$autoincrement[$type] = 1;
        } elseif($autoincrement) {
            self::$autoincrement[$type]++;
        }

        return self::$autoincrement[$type];
    }
    
    public static function translate($translationLabel, $defaultLabel, $params = []) {
        $label = __($translationLabel, $params);
        return ($label !== $translationLabel) ? $label : self::getFormattedName($defaultLabel);
    }
    
    public static function translateModelData($translationLabel) {
        return __($translationLabel, ['type' => __('models_labels.' .  self::$modelType . '.label_single')]);
    }
    
    public static function translateModelDataPlural($translationLabel) {
        return __($translationLabel, ['type' => __('models_labels.' .  self::$modelType . '.label_plural')]);
    }
}