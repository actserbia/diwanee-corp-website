<?php
namespace App\Validation;

use Illuminate\Support\Facades\Validator;
use App\TagType;
use App\Tag;


class Rules {
    public static function addRules() {
        self::addCheckSubtype();
        self::addCheckTags();
    }
    
    private static function addCheckSubtype() {
        Validator::extend('checkSubtype', function ($attribute, $value, $parameters) {
            $subtype = TagType::find($value);
            if(isset($subtype->parentType) && $subtype->parentType->id != $parameters[0]) {
                return false;
            }
            if(isset($subtype->subtype)) {
                $tempTagType = $subtype->subtype;
                while($tempTagType !== null && $tempTagType->id != $parameters[0]) {
                    $tempTagType = isset($tempTagType->subtype) ? $tempTagType->subtype : null;
                }
                if($tempTagType !== null) {
                    return false;
                }
            }
            
            if(!self::checkTags($parameters[0], 'children', $value)) {
                return false;
            }
            
            if(!self::checkTags($value, 'parents', $parameters[0])) {
                return false;
            }
            
            return true;
        });
    }
    
    protected static function checkTags($tagType, $compareType, $compareTagType) {
        $tags = Tag::where('tag_type_id', '=', $tagType)->get();
        foreach($tags as $tag) {
            if(!self::checkTag($tag, $compareType, $compareTagType)) {
                return false;
            }
        }
        
        return true;
    }
    
    protected static function checkTag($tag, $compareType, $compareTagType) {
        if(empty($tag->$compareType)) {
            return true;
        }
        
        foreach($tag->$compareType as $compareTag) {
            if($compareTag->tagType->id != $compareTagType) {
                return false;
            }
        }
        
        return true;
    }

    private static function addCheckTags() {
        Validator::extend('checkTags', function ($attribute, $value, $parameters) {
            if(empty($value)) {
                return true;
            }
            
            $tagIds = is_array($value) ? $value : [$value];
            $type = ($attribute === 'parents') ? 'parentType' : 'subtype';
            return self::checkTagTypes($parameters[0], $tagIds, $type);
        });
    }
    
    protected static function checkTagTypes($tagTypeId, $tagIds, $type) {
        $tagType = TagType::find($tagTypeId);
        
        if(!isset($tagType->$type->id)) {
            return false;
        }
        
        foreach($tagIds as $tagId) {
            if(!self::checkTagType($tagId, $tagType->$type->id)) {
                return false;
            }
        }
        
        return true;
    }
    
    private static function checkTagType($tagId, $tagTypeId) {
        $tag = Tag::find($tagId);
        return isset($tag) && $tag['tagType']['id'] === $tagTypeId;
    }
}
