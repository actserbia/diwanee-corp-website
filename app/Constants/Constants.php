<?php
namespace App\Constants;

use ReflectionClass;
use App\Utils\Utils;

trait Constants {
    public static function getAll() {
        $refClass = new ReflectionClass(__CLASS__);
        return array_filter($refClass->getConstants(), function($value) { return !is_array($value); });
    }

    public static function getAllForDropdown() {
        return self::getForDropdown(static::getAll());
    }

    public static function getForDropdown($constants) {
        $refClass = new ReflectionClass(__CLASS__);
        return Utils::getForDropdown($constants, 'constants.' . $refClass->getShortName());
    }
}