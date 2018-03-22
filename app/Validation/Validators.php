<?php
namespace App\Validation;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\FieldType;
use App\Field;
use App\NodeType;
use App\Tag;
use App\Node;
use App\Constants\Models;

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
        
        return Validator::make($data, [
            'name' => self::modelRequiredValidation('name', $model) . '|' . self::uniqueValidation('field_types', 'name', 'category', 'tag', $additional) . '|max:255'
        ]);
    }
    
    public static function tagsFormValidator(array $data, array $additional = []) {
        $model = new Tag;

        $id = isset($additional['id']) ? $additional['id'] : '';

        return Validator::make($data, [
            'name' => self::modelRequiredValidation('name', $model) . '|' . self::uniqueValidation('tags', 'name', 'tag_type_id', $data['tag_type'], $additional) . '|max:255',
            'tag_type' => self::modelRequiredValidation('tag_type', $model) . '|exists:field_types,id',
            'parents' => 'checkTags:' . $data['tag_type'] . ',' . $id . ',' . json_encode($data['children']) . '|' . 'checkTagMaxLevel:' . json_encode($data['children']),
            'children' => 'checkTags:' . $data['tag_type'] . ',' . $id . ',' . json_encode($data['parents'])
        ]);
    }
    
    public static function nodeTypesFormValidator(array $data, array $additional = []) {
        $model = new NodeType;
        $fieldModel = new Field;
        
        $nameUnique = isset($additional['id']) ? 'unique:node_types,id,' . $additional['id'] : 'unique:node_types';
        
        return Validator::make($data, [
            'name' => self::modelRequiredValidation('name', $model) . '|' . $nameUnique . '|max:255',
            'new_items.attribute_fields.*.title' => self::modelRequiredValidation('title', $fieldModel) . '|max:2'
        ]);
    }
    
    public static function fieldsFormValidator(array $data, array $additional = []) {
        $model = new Field;
        
        return Validator::make($data, [
            'title' => self::modelRequiredValidation('title', $model) . '|' . self::uniqueValidation('fields', 'title', 'field_type_id', $data['attribute_field_type'], $additional) . '|max:255'
        ]);
    }
    
    public static function nodesFormValidator(array $data, array $additional = []) {
        $model = new Node(['model_type_id' => $data['model_type']]);
        
        $validationParams = [
            'title' => self::modelRequiredValidation('title', $model) . '|' . self::uniqueValidation('nodes', 'title', 'node_type_id', $data['model_type'], $additional) . '|max:255',
            'model_type' => self::modelRequiredValidation('model_type', $model) . '|exists:node_types,id'
        ];
        
        foreach($model->getFillableAttributes() as $attribute) {
            if($model->attributeType($attribute) === Models::AttributeType_Json) {
                $validationParams[$attribute] = 'checkJson';
            }
        }
        
        foreach($model->modelType->tag_fields as $tagField) {
            if($model->isRequired($tagField->formattedTitle)) {
                $validationParams[$tagField->formattedTitle] = 'checkTagRequired';
            }
        }

        return Validator::make($data, $validationParams);
    }
    
    public static function nodeListsFormValidator(array $data, array $additional = []) {
        $model = new NodeType;

        $nameUnique = isset($additional['id']) ? 'unique:node_lists,id,' . $additional['id'] : 'unique:node_lists';
        
        return Validator::make($data, [
            'name' => self::modelRequiredValidation('name', $model) . '|' . $nameUnique . '|max:255'
        ]);
    }


    private static function modelRequiredValidation($field, $model) {
        return $model->isRequired($field) ? 'required' : 'nullable';
    }
    
    private static function uniqueValidation($table, $field, $categoryField, $categoryFieldValue, $additional) {
        $id = isset($additional['id']) ?  $additional['id'] : 'null';
        return 'unique:' . $table . ',' . $field . ',' . $id . ',id,' . $categoryField . ',' . $categoryFieldValue;
    }
}
