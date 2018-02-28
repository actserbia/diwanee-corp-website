<?php
namespace App\Validation;

use Illuminate\Support\Facades\Validator;
use App\Validation\Rules\CheckSTContent;
use App\User;
use App\FieldType;
use App\Field;
use App\NodeType;
use App\Tag;
use App\Node;

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
        $model = new FieldType;

        $nameUnique = isset($additional['id']) ? 'unique:field_types,id,' . $additional['id'] : 'unique:field_types';
        
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
            'tag_type' => self::modelRequiredValidation('tag_type', $model) . '|exists:field_types,id',
            'parents' => 'checkTags:' . $data['tag_type'] . ',' . $id . ',' . json_encode($data['children']) . '|' . 'checkTagMaxLevel:' . json_encode($data['children']),
            'children' => 'checkTags:' . $data['tag_type'] . ',' . $id . ',' . json_encode($data['parents'])
        ]);
    }
    
    public static function nodeTypesFormValidator(array $data, array $additional = []) {
        $model = new NodeType;

        $nameUnique = isset($additional['id']) ? 'unique:node_types,id,' . $additional['id'] : 'unique:node_types';
        
        return Validator::make($data, [
            'name' => self::modelRequiredValidation('name', $model) . '|' . $nameUnique . '|max:255',
            'status' => self::modelRequiredValidation('status', $model)
        ]);
    }
    
    public static function fieldsFormValidator(array $data, array $additional = []) {
        $model = new Field;

        $titleUnique = isset($additional['id']) ? 'unique:fields,id,' . $additional['id'] : 'unique:fields';
        
        return Validator::make($data, [
            'title' => self::modelRequiredValidation('title', $model) . '|' . $titleUnique . '|max:255'
        ]);
    }
    
    public static function nodesFormValidator(array $data, array $additional = []) {
        $model = new Node(['model_type_id' => $data['model_type']]);

        $titleUnique = isset($additional['id']) ? 'unique:nodes,id,' . $additional['id'] : 'unique:nodes';
        $id = isset($additional['id']) ? $additional['id'] : '';

        return Validator::make($data, [
            'title' => self::modelRequiredValidation('title', $model) . '|' . $titleUnique . '|max:255',
            'model_type' => self::modelRequiredValidation('model_type', $model) . '|exists:node_types,id',
            'content' => [new CheckSTContent]
        ]);
    }


    private static function modelRequiredValidation($field, $model) {
        return $model->isRequired($field) ? 'required' : 'nullable';
    }
}
