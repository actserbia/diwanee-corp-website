<?php

namespace App\Constants;

use ReflectionClass;

trait ConstantsTrait {
    public static function getAll() {
        $refClass = new ReflectionClass(__CLASS__);
        $all = array_values($refClass->getConstants());
        
        return $all;
    }
    
    public static function getAllForDropdown() {
        $refClass = new ReflectionClass(__CLASS__);
        return self::getForDropdown($refClass->getConstants());
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