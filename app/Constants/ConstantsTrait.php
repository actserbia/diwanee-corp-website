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
        $all = array();
        
        $refClass = new ReflectionClass(__CLASS__);
        foreach($refClass->getConstants() as $constant) {
            $all[$constant] = __('database.' . $refClass->getShortName() . '.' . $constant);
        }
        
        return $all;
    }
}