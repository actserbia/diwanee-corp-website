<?php
namespace App\GraphQL;

use App\Utils\Utils;

class GraphQLUtils {
    public static function getTypes() {
        return array_merge(self::getTypesFromFolder(''), self::getTypesFromFolder('NodeModel'));
    }

    private static function getTypesFromFolder($folderName = '') {
        $types = [];

        $folder = empty($folderName) ? $folderName : $folderName . '\\';

        foreach(self::getGraphQLTypesNames($folderName) as $typeName) {
            $types[$typeName] = 'App\\GraphQL\\Type\\' . $folder . $typeName . 'sType';
        }

        return $types;
    }

    public static function getSchemas() {
        return array_merge(self::getSchemasFromFolder(''), self::getSchemasFromFolder('NodeModel'));
    }

    private static function getSchemasFromFolder($folderName) {
        $schemas = [];

        $folder = empty($folderName) ? $folderName : $folderName . '\\';

        foreach(self::getGraphQLTypesNames($folderName) as $typeName) {
            $schemas[Utils::getFormattedDBName($typeName)] = [
                'query' => [
                    Utils::getFormattedDBName($typeName) . 's' => 'App\\GraphQL\\Query\\' . $folder . $typeName . 'sQuery',
                ],
                'mutation' => []
            ];
        }

        return $schemas;
    }

    private static function getGraphQLTypesNames($folderName = '') {
        $typeNames = [];

        if(!empty($folderName)) {
            $folderName .= '/';
        }

        $folder = app_path() . '/GraphQL/Type/' . $folderName;
        $files = glob($folder . '*.php');
        foreach($files as $file) {
            $typeNames[] = str_replace('sType.php', '', str_replace($folder, '', $file));
        }

        return $typeNames;
    }
}