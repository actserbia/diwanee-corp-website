<?php
namespace App\Validation;

use Illuminate\Support\Facades\Validator;
use App\Tag;


class Rules {
    public static function addRules() {
        self::addCheckTagType();
        self::addCheckParentsAndChildren();
        self::addCheckEqual();
    }
    
    private static function addCheckTagType() {
        Validator::extend('checkTagType', function ($attribute, $value, $parameters) {
            $tag = Tag::find($value);
            return $tag['type'] == $parameters[0];
        });
    }
    
    private static function addCheckParentsAndChildren() {
        Validator::extend('checkParentsAndChildren', function ($attribute, $value, $parameters) {
            if($attribute === 'parents' && $parameters[0] !== 'subcategory' && !empty($value)) {
                return false;
            }
            
            if($attribute === 'children' && $parameters[0] !== 'category' && !empty($value)) {
                return false;
            }
            
            return true;
        });
    }

    private static function addCheckEqual() {
        Validator::extend('checkEqual', function ($attribute, $value, $parameters) {
            return $value == $parameters[0];
        });
    }
}
