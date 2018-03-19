<?php

namespace App\Models\Node\ClassGenerator;

use App\Constants\Settings;
use App\Utils\FileFunctions;
use App\NodeType;
use Illuminate\Support\Str;

abstract class ClassGenerator {
    const FOLDER = '';

    protected $model = null;
    protected $filepath = null;
    protected $oldFilepath = null;

    protected $content = '';

    public function __construct($model, $oldNodeTypeName = null) {
        $this->model = $model;

        if(isset($oldNodeTypeName)) {
            $this->oldFilepath = $this->getClassFilename($oldNodeTypeName);
        }

        $this->filepath = $this->getClassFilename($model->name);
    }

    protected function getClassFilename($modelName) {
        return app_path() . '/' . static::FOLDER . '/' . $this->getModelClassName($modelName) . '.php';
    }

    protected function getModelClassName($modelName) {
        return ucfirst(Settings::NodeModelPrefix) . Str::studly($modelName);
    }

    public function generate() {
        if(isset($this->oldFilepath) && $this->oldFilepath !== $this->filepath) {
            FileFunctions::deleteFile($this->oldFilepath);
        }

        if(count($this->model->attribute_fields) > 0) {
            $this->populateData();

            $this->populateContent();

            FileFunctions::writeToFile($this->content, $this->filepath);
        } else {
            FileFunctions::deleteFile($this->filepath);
        }
    }

    abstract protected function populateData();

    abstract protected function populateContent();

    protected function addFormattedList($listName) {
        if(!empty($this->$listName)) {
            $this->content .= str_repeat(' ', 8) . 'protected $' . $listName . ' = [\'' . implode('\', \'', $this->$listName) . '\']' . ';' . PHP_EOL . PHP_EOL;
        }
    }

    protected function addFormattedListWithKeys($listName) {
        if(!empty($this->$listName)) {
            $this->content .= str_repeat(' ', 8) . 'protected $' . $listName . ' = [' . PHP_EOL;

            foreach($this->$listName as $listItemKey => $listItemValue) {
                $this->content .= $this->addFormattedListWithKeysItem($listItemKey, $listItemValue);
            }

            $this->content .= str_repeat(' ', 8) . '];' . PHP_EOL . PHP_EOL;
        }
    }

    protected function addFormattedListWithKeysItem($listItemKey, $listItemValue) {
        $this->content .= str_repeat(' ', 12) . '\'' . $listItemKey . '\' => ';
        if(is_array($listItemValue)) {
            $this->content .= '[' . PHP_EOL;
            foreach($listItemValue as $key => $value) {
                $this->content .= str_repeat(' ', 16) . '\'' . $key . '\' => ' . $this->getValue($value) . ',' . PHP_EOL;
            }
            $this->content .= str_repeat(' ', 12) . ']';
        } else {
            $this->content .= $this->getValue($listItemValue);
        }
        $this->content .= ',' . PHP_EOL;
    }

    private function getValue($value) {
        if(is_string($value) && strpos($value, '::') === false) {
            return '\'' . addslashes($value) . '\'';
        }
        
        if(is_array($value)) {
            return '[\'' . implode('\',\'', $value) . '\']';
        }
        
        return $value;
    }

    public static function generateAll() {
        $nodeTypes = NodeType::get();
        foreach($nodeTypes as $nodeType) {
            $generator = new self($nodeType);
            $generator->generate();
        }
    }

    public static function deleteAll() {
        $folder = app_path() . '/' . static::FOLDER;
        $files = glob($folder . '/*');
        foreach($files as $file) {
            if(is_file($file)) {
                unlink($file);
            }
        }
    }

    public static function generateAllFiles() {
        NodeModelClassGenerator::generateAll();
        GraphQLTypeClassGenerator::generateAll();
        GraphQLQueryClassGenerator::generateAll();
    }

    public static function deleteAllGeneratedFiles() {
        NodeModelClassGenerator::deleteAll();
        GraphQLTypeClassGenerator::deleteAll();
        GraphQLQueryClassGenerator::deleteAll();
    }

    public static function generateAllFilesForNodeType($nodeType, $oldName = null) {
        $nodeModelClassGenerator = new NodeModelClassGenerator($nodeType, $oldName);
        $nodeModelClassGenerator->generate();

        $graphQlTypeClassGenerator = new GraphQLTypeClassGenerator($nodeType, $oldName);
        $graphQlTypeClassGenerator->generate();

        $graphQlQueryClassGenerator = new GraphQLQueryClassGenerator($nodeType, $oldName);
        $graphQlQueryClassGenerator->generate();
    }
}