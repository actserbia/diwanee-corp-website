<?php

namespace App\Constants;

use ReflectionClass;

trait ConstantsTrait {
    public static function getAll() {
        $all = array();
        
        $refClass = new ReflectionClass(__CLASS__);
        foreach($refClass->getConstants() as $constant) {
            $all[$constant] = __('database.' . $refClass->getShortName() . '.' . $constant);
        }
        
        return $all;
    }
}