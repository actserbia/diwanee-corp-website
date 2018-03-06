<?php
namespace App\Utils;

class Utils {
    private static $autoincrement = [];
    public static $modelType;

    public static function getAllDirectClassesFromNamespace($namespace, $shortName = false) {
        $composer = require base_path() . '/vendor/autoload.php';

        $classes = array_keys($composer->getClassMap());
        
        $namespaceClasses = array_filter($classes, function($class) use($namespace) {
            $classParts = [];
            if(strpos($class, $namespace) === 0) {
                $classParts = preg_split('/\\\/', str_replace($namespace . '\\', '', $class));
            }
            return (count($classParts) === 1);
        });

        if($shortName) {
            array_walk($namespaceClasses, function(&$class) use($namespace) {
                $class = str_replace($namespace . '\\', '', $class);
            });
        }

        return $namespaceClasses;
    }
    
    public static function getForDropdown($items, $prefix = '') {
        $dropdownList = array();

        foreach($items as $item) {
            $dropdownList[$item] = empty($prefix) ? $item : __($prefix . '.' . $item);
        }

        return $dropdownList;
    }
    
    public static function getFormattedName($name, $delimiter = '_', $newDelimiter = '') {
        return str_replace($delimiter, $newDelimiter, ucwords($name, $delimiter));
    }
    
    public static function getFormattedDBName($name, $delimiter = '_') {
        return str_replace(' ', $delimiter, trim(strtolower(str_replace('  ', ' ', preg_replace("([A-Z])", " $0", $name)))));
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
        return ($label !== $translationLabel) ? $label : self::getFormattedName($defaultLabel, '_', ' ');
    }
    
    public static function translateModelData($translationLabel) {
        return __($translationLabel, ['type' => __('models_labels.' .  self::$modelType . '.label_single')]);
    }
    
    public static function translateModelDataPlural($translationLabel) {
        return __($translationLabel, ['type' => __('models_labels.' .  self::$modelType . '.label_plural')]);
    }
}