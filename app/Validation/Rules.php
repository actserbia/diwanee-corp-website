<?php
namespace App\Validation;

use Illuminate\Support\Facades\Validator;
use App\Tag;
use App\Constants\Settings;

class Rules {
    public static function addRules() {
        self::addCheckTags();
        self::addCheckTagRequired();
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
        
        Validator::extend('checkTagMaxLevel', function ($attribute, $value, $parameters) {
            if(!self::checkTagMaxLevel($attribute, $value, $parameters)) {
                return false;
            }

            return true;
        });
    }
    
    private static function checkTagTypes($value, $parameters) {
        $tagIds = is_array($value) ? $value : [$value];
        foreach($tagIds as $tagId) {
            $tag = Tag::find($tagId);
            if(!isset($tag) || $tag->tag_type->id != $parameters[0]) {
                return false;
            }
        }

        return true;
    }
    
    private static function checkTagParentsAndChildren($attribute, $value, $parameters) {
        $tagIds = is_array($value) ? $value : [$value];
        
        $relationIds = is_array(json_decode($parameters[2])) ? json_decode($parameters[2]) : [json_decode($parameters[2])];
        if(!empty(array_intersect($tagIds, $relationIds))) {
            return false;
        }
        
        if(empty($parameters[1])) {
            return true;
        }

        $currentTag = Tag::find($parameters[1]);
        if($attribute === 'children' && !empty(array_intersect($tagIds, $currentTag->relationIds('parents')))) {
            return false;
        }
        if($attribute === 'parents' && !empty(array_intersect($tagIds, $currentTag->relationIds('children')))) {
            return false;
        }

        return true;
    }
    
    private static function checkTagMaxLevel($attribute, $value, $parameters) {
        $tagIds = is_array($value) ? $value : [$value];
        
        $relationIds = is_array(json_decode($parameters[0])) ? json_decode($parameters[0]) : [json_decode($parameters[0])];
        
        $tags = Tag::whereIn('id', $tagIds)->get();
        $relationTags = Tag::whereIn('id', $relationIds)->get();
        
        $relationAttribute = ($attribute === 'children') ? 'parents' : 'children';
        
        $tagsMaxLevel = Tag::relationMaxLevelsCount($attribute, $tags);
        $relationMaxLevel = Tag::relationMaxLevelsCount($relationAttribute, $relationTags);
        
        $levels = $tagsMaxLevel + $relationMaxLevel;
        if(!empty($tagIds)) {
            $levels++;
        }
        if(!empty($relationIds)) {
            $levels++;
        }
        if($levels > Settings::MaximumTagsLevelsCount) {
            return false;
        }

        return true;
    }



    private static function addCheckTagRequired() {
        Validator::extend('checkTagRequired', function ($attribute, $value, $parameters) {
            if(is_array($value)) {
                $value = array_filter($value, function ($item) {
                    return !empty($item);
                });
            }

            return !empty($value);
        });
    }
}
