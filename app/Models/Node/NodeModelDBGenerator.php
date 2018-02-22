<?php

namespace App\Models\Node;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Constants\FieldType;
use App\Utils\Utils;
use App\NodeType;
use App\Constants\Settings;
use App\Constants\FieldTypeCategory;

class NodeModelDBGenerator {
    private $model = null;
    private $tableName = null;
    private $oldTableName = null;

    public function __construct($model, $oldNodeTypeName = null) {
        $this->model = $model;
        $this->tableName = self::getTableName($this->model->name, Settings::NodeModelPrefix);

        if(isset($oldNodeTypeName)) {
            $this->oldTableName = self::getTableName($oldNodeTypeName, Settings::NodeModelPrefix);
        }
    }

    private static function getTableName($modelName, $prefix) {
        return $prefix . '_' . Utils::getFormattedDBName($modelName) . 's';
    }

    public function generate() {
        if(isset($this->oldTableName)) {
            if($this->oldTableName !== $this->tableName) {
                Schema::rename($this->oldTableName, $this->tableName);
            }
        }

        if(Schema::hasTable($this->tableName)) {
            $this->updateNodeModelTable();
        } else {
            $this->createNodeModelTable();
        }

        //$this->addMultipleFields();
    }

    private function createNodeModelTable() {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_id');

            $this->addNodeModelTableFields($table);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('node_id')->references('id')->on('nodes');
        });
    }

    private function updateNodeModelTable() {
        Schema::table($this->tableName, function (Blueprint $table) {
            $this->addNodeModelTableFields($table);
        });
    }

    private function addNodeModelTableFields($table) {
        $attributeFieldsRelationName = FieldTypeCategory::Attribute . '_fields';
        foreach($this->model->$attributeFieldsRelationName as $field) {
            //if(!$field->pivot->multiple && !Schema::hasColumn($this->tableName, $field->formattedTitle)) {
            if(!Schema::hasColumn($this->tableName, $field->formattedTitle)) {
                $this->setTableField($table, $field);
            }
        }
    }

    /*private function addMultipleFields() {
        foreach($this->model->fields as $field) {
            $fieldsTableName = self::getTableName($field->formattedTitle, Settings::NodeModelFieldPrefix);
            if($field->pivot->multiple && !Schema::hasTable($fieldsTableName)) {
                $this->createNodeModelFieldsTable($fieldsTableName, $field);
            }
        }
    }

    private function createNodeModelFieldsTable($fieldsTableName, $field) {
        Schema::create($fieldsTableName, function (Blueprint $table) use ($field) {
            $table->increments('id');
            $table->unsignedInteger('node_id');
            $table->unsignedInteger('field_id');

            $this->setTableField($table, $field);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('node_id')->references('id')->on('nodes');
            $table->foreign('field_id')->references('id')->on('fields');
        });
    }*/

    private function setTableField($table, $field) {
        $tableField = null;
        switch($field->field_type->name) {
            case FieldType::Text:
                $tableField = $table->string($field->formattedTitle, 255);
                break;

            case FieldType::Integer:
                $tableField = $table->unsignedInteger($field->formattedTitle);
                break;

            case FieldType::Date:
                $tableField = $table->timestamp($field->formattedTitle);
                break;
        }

        if(!$field->pivot->required) {
            $tableField->nullable();
        }
    }

    public static function changeFieldNameInAllNodeTables($oldFieldName, $newFieldName) {
        $nodeTypes = NodeType::get();
        foreach($nodeTypes as $nodeType) {
            $generator = new self($nodeType);
            $generator->changeFieldName($oldFieldName, $newFieldName);
        }

        /*$oldFieldsTableName = self::getTableName($oldFieldName, Settings::NodeModelFieldPrefix);
        $newFieldsTableName = self::getTableName($newFieldName, Settings::NodeModelFieldPrefix);
        if(Schema::hasTable($oldFieldsTableName)) {
            Schema::rename($oldFieldsTableName, $newFieldsTableName);
            Schema::table($newFieldsTableName, function (Blueprint $table) use ($oldFieldName, $newFieldName) {
                $table->renameColumn($oldFieldName, $newFieldName);
            });
        }*/
    }

    public function changeFieldName($oldFieldName, $newFieldName) {
        Schema::table($this->tableName, function (Blueprint $table) use ($oldFieldName, $newFieldName) {
            $table->renameColumn($oldFieldName, $newFieldName);
        });
    }

    public static function deleteAll() {
        $nodeTypes = NodeType::get();
        foreach($nodeTypes as $nodeType) {
            $tableName = self::getTableName($nodeType->name, Settings::NodeModelPrefix);
            Schema::dropIfExists($tableName);

            $attributeFieldsRelationName = FieldTypeCategory::Attribute . '_fields';
            foreach($nodeType->$attributeFieldsRelationName as $field) {
                $fieldsTableName = self::getTableName($field->formattedTitle, Settings::NodeModelFieldPrefix);
                Schema::dropIfExists($fieldsTableName);
            }
        }
    }
}