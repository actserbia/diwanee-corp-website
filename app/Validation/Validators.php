<?php
namespace App\Validation;

use Illuminate\Support\Facades\Validator;
use App\Validation\Rules\CheckSTContent;
use App\Constants\ElementType;
use App\Constants\TagType;
use App\Constants\VideoProvider;

class Validators {
    public static function articlesFormValidator(array $data, array $additional = []) {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'external_url' => 'nullable|url',
            'publication' => 'nullable|exists:tags,id|checkTagType:publication',
            'brand' => 'nullable|exists:tags,id|checkTagType:brand',
            'category' => 'required|exists:tags,id|checkTagType:category',
            'influencer' => 'nullable|exists:tags,id|checkTagType:influencer',
            'subcategories.*' => 'exists:tags,id|checkTagType:subcategory',
            'content' => [new CheckSTContent]
        ]);
    }
    
    public static function articlesValidator(array $params, array $additional = []) {
        return Validator::make($params, [
            'ids' => 'nullable',
            'active' => 'nullable|in:true,false',
            'tags.*' => 'nullable|exists:tags,name',
            'perPage' => 'nullable|integer',
            'page' => 'nullable|integer',
            'elementsTypes.*' => 'nullable|in:' . implode(',', ElementType::getAll()),
            'videoProviders.*' => 'nullable|in:' . implode(',', VideoProvider::getAll()),
            'remoteIds.*' => 'nullable|string',
            'authorsNames.*' => 'nullable|exists:users,name',
            'authorsEmails.*' => 'nullable|exists:users,email',
            'authorsRoles.*' => 'nullable|exists:users,role'
        ]);
    }
    
    public static function tagsFormValidator(array $data, array $additional = []) {
        $nameUnique = isset($additional['id']) ? 'unique:tags,id,' . $additional['id'] : 'unique:tags';

        return Validator::make($data, [
            'name' => 'required|' . $nameUnique . '|max:255',
            'type' => 'required|in:' . implode(',', TagType::getAll()),
            'parents.*' => 'exists:tags,id|checkTagType:category',
            'children.*' => 'exists:tags,id|checkTagType:subcategory',
            'parents' => 'checkParentsAndChildren:' . $data['type'],
            'children' => 'checkParentsAndChildren:' . $data['type']
        ]);
    }
    
    public static function tagsValidator(array $params, array $additional = []) {
        return Validator::make($params, [
            'perPage' => 'nullable|integer',
            'page' => 'nullable|integer',
            'types.*' => 'nullable|in:' . implode(',', TagType::getAll())
        ]);
    }
    
    public static function usersFormValidator(array $data, array $additional = []) {
        $emailUnique = isset($additional['id']) ? 'unique:users,id,' . $additional['id'] : 'unique:users';
        $password = isset($data['password']) ? 'required' : 'nullable';

        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|' . $emailUnique,
            'password' => $password . '|string|min:6|confirmed',
            'role' => 'required'
        ]);
    }
}
