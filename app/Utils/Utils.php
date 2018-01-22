<?php
namespace App\Utils;

class Utils {
    private static $autoincrement = [];

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

    public static function removeEmptyValues($list) {
        return array_filter($list, function ($item) {
            return $item !== '';
        });
    }
}