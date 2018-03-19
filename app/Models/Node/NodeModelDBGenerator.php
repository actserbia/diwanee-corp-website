<?php

namespace App\Models\Node;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Constants\AttributeFieldType;
use App\NodeType;
use App\Constants\FieldTypeCategory;

class NodeModelDBGenerator {
    private $model = null;
    private $tableName = null;
    private $oldTableName = null;

    public function __construct($model, $oldTableName = null) {
        $this->model = $model;
        $this->tableName = $this->model->additionalDataTableName;

        if(isset($oldTableName)) {
            $this->oldTableName = $oldTableName;
        }
    }

    public function generate() {
        if(isset($this->oldTableName)) {
            if($this->oldTableName !== $this->tableName) {
                Schema::rename($this->oldTableName, $this->tableName);
            }
        }

        if(count($this->model->attribute_fields) > 0) {
            if(Schema::hasTable($this->tableName)) {
                $this->updateNodeModelTable();
            } else {
                $this->createNodeModelTable();
            }
        } else {
            Schema::dropIfExists($this->tableName);
        }
        
    }

    private function createNodeModelTable() {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_id');

            $this->addNodeModelTableFields($table);
            
            $table->foreign('node_id')->references('id')->on('nodes');
        });
    }

    private function updateNodeModelTable() {
        Schema::table($this->tableName, function (Blueprint $table) {
            $this->addNodeModelTableFields($table);
        });
    }

    private function addNodeModelTableFields($table) {
        foreach($this->model->attribute_fields as $field) {
            if(!Schema::hasColumn($this->tableName, $field->formattedTitle)) {
                $this->setTableField($table, $field);
            }
        }
    }

    private function setTableField($table, $field) {
        $databaseType = AttributeFieldType::databaseTypes[$field->field_type->name];
        if(is_array($databaseType)) { 
            $databaseTypeName = $databaseType[0];
            $tableField = $table->$databaseTypeName($field->formattedTitle, $databaseType[1]);
        } else {
            $tableField = $table->$databaseType($field->formattedTitle);
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
    }

    public function changeFieldName($oldFieldName, $newFieldName) {
        Schema::table($this->tableName, function (Blueprint $table) use ($oldFieldName, $newFieldName) {
            $table->renameColumn($oldFieldName, $newFieldName);
        });
    }

    public static function deleteAll() {
        $nodeTypes = NodeType::get();
        foreach($nodeTypes as $nodeType) {
            $tableName = $nodeType->additionalDataTableName;
            Schema::dropIfExists($tableName);
        }
    }
}