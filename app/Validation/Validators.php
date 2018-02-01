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
        
        return Validator::make($data, [
            'name' => self::modelRequiredValidation('name', $model) . '|' . $nameUnique . '|max:255'
        ]);
    }
    
    public static function tagsFormValidator(array $data, array $additional = []) {
        $model = new Tag;

        $nameUnique = isset($additional['id']) ? 'unique:tags,id,' . $additional['id'] : 'unique:tags';
        $id = isset($additional['id']) ? $additional['id'] : '';

        return Validator::make($data, [
            'name' => self::modelRequiredValidation('name', $model) . '|' . $nameUnique . '|max:255',
            'tagType' => self::modelRequiredValidation('tagType', $model) . '|exists:tag_types,id',
            'parents' => 'checkTags:' . $data['tagType'] . ',' . $id . ',' . json_encode($data['children']),
            'children' => 'checkTags:' . $data['tagType'] . ',' . $id . ',' . json_encode($data['parents'])
        ]);
    }


    private static function modelRequiredValidation($field, $model) {
        return $model->required($field) ? 'required' : 'nullable';
    }
}
