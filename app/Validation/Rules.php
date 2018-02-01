<?php
namespace App\Validation;

use Illuminate\Support\Facades\Validator;
use App\Tag;


class Rules {
    public static function addRules() {
        self::addCheckTags();
    }
    
    private static function addCheckTags() {
        Validator::extend('checkTags', function ($attribute, $value, $parameters) {
            if(empty($value)) {
                return true;
            }

            if(!self::checkTagTypes($value, $parameters)) {
                return false;
            }
            
            if(!self::checkTagParentsAndChildren($attribute, $value, $parameters)) {
                return false;
            }

            return true;
        });
    }
    
    private static function checkTagTypes($value, $parameters) {
        $tagIds = is_array($value) ? $value : [$value];
        foreach($tagIds as $tagId) {
            $tag = Tag::find($tagId);
            $id = $tag->tagType->id;
            if(!isset($tag) || $tag->tagType->id != $parameters[0]) {
                return false;
            }
        }

        return true;
    }
    
    private static function checkTagParentsAndChildren($attribute, $value, $parameters) {
        $tagIds = is_array($value) ? $value : [$value];
        
        $relationIds = is_array(json_decode($parameters[2])) ? json_decode($parameters[2]) : [json_decode($parameters[2])];
        foreach($tagIds as $tagId) {
            if(in_array($tagId, $relationIds)) {
                return false;
            }
        }
        
        if(empty($parameters[1])) {
            return true;
        }
        
        $currentTag = Tag::find($parameters[1]);
        foreach($tagIds as $tagId) {
            if(in_array($tagId, $currentTag->relationIds('parents', $attribute !== 'parents'))) {
                return false;
            }

            if(in_array($tagId, $currentTag->relationIds('children', $attribute !== 'children'))) {
                return false;
            }
        }

        return true;
    }
}
