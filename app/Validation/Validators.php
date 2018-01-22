<?php
namespace App\Validation;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\TagType;
use App\Tag;

class Validators {
    public static function usersFormValidator(array $data, array $additional = []) {
        $model = new User;

        $emailUnique = isset($additional['id']) ? 'unique:users,id,' . $additional['id'] : 'unique:users';
        $password = isset($data['password']) ? 'required' : 'nullable';

        return Validator::make($data, [
            'name' => self::modelRequiredValidation('name', $model) . '|string|max:255',
            'email' => self::modelRequiredValidation('email', $model) . '|string|email|max:255|' . $emailUnique,
            'password' => $password . '|string|min:6|confirmed',
            'role' => self::modelRequiredValidation('role', $model)
        ]);
    }
    
    public static function tagTypesFormValidator(array $data, array $additional = []) {
        $model = new TagType;

        $nameUnique = isset($additional['id']) ? 'unique:tag_types,id,' . $additional['id'] : 'unique:tag_types';
        
        $subtypeValidation = self::modelRequiredValidation('subtype', $model);
        if(isset($data['subtype'])) {
            $subtypeValidation .= '|exists:tag_types,id';
        } else {
            $data['subtype'] = 0;
        }
        if(isset($additional['id'])) {
            $data['id'] = $additional['id'];
            $subtypeValidation .= '|different:id|checkSubtype:' . $additional['id'];
        }
        
        return Validator::make($data, [
            'name' => self::modelRequiredValidation('name', $model) . '|' . $nameUnique . '|max:255',
            'subtype' => $subtypeValidation,
        ]);
    }
    
    public static function tagsFormValidator(array $data, array $additional = []) {
        $model = new Tag;

        $nameUnique = isset($additional['id']) ? 'unique:tags,id,' . $additional['id'] : 'unique:tags';

        return Validator::make($data, [
            'name' => self::modelRequiredValidation('name', $model) . '|' . $nameUnique . '|max:255',
            'tagType' => self::modelRequiredValidation('tagType', $model) . '|exists:tag_types,id',
            'parents' => 'checkTags:' . $data['tagType'],
            'children' => 'checkTags:' . $data['tagType']
        ]);
    }


    private static function modelRequiredValidation($field, $model) {
        return $model->required($field) ? 'required' : 'nullable';
    }
}
