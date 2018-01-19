<?php

namespace App\Constants;

use ReflectionClass;

trait ConstantsTrait {
    public static function getAll() {
        $refClass = new ReflectionClass(__CLASS__);
        return array_filter($refClass->getConstants(), function($value) { return !is_array($value); });
    }
    
    public static function getAllForDropdown() {
        return self::getForDropdown(self::getAll());
    }

    public static function getForDropdown($constants) {
        $dropdownList = array();
        
        $refClass = new ReflectionClass(__CLASS__);
        foreach($constants as $constant) {
            $dropdownList[$constant] = __('database.' . $refClass->getShortName() . '.' . $constant);
        }
        
        return $dropdownList;
    }
}