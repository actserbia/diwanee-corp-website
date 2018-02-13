<?php

namespace App\Models\Node;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Constants\FieldType;
use App\Utils\Utils;
use App\NodeType;

class NodeModelDBGenerator {
    private $model = null;
    private $tableName = null;
    private $oldTableName = null;

    public function __construct($model, $oldNodeTypeName = null) {
        $this->model = $model;
        $this->tableName = $this->getTableName($this->model->name);

        if(isset($oldNodeTypeName)) {
            $this->oldTableName = $this->getTableName($oldNodeTypeName);
        }
    }

    public function generate() {
        if(isset($this->oldTableName)) {
            if($this->oldTableName !== $this->tableName) {
                Schema::rename($this->oldTableName, $this->tableName);
            }
        }

        if(Schema::hasTable($this->tableName)) {
            $this->updateDBTable();
        } else {
            $this->createDBTable();
        }
    }

    private function createDBTable() {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_id');

            $this->addDBFields($table);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('node_id')->references('id')->on('nodes');
        });
    }

    private function updateDBTable() {
        Schema::table($this->tableName, function (Blueprint $table) {
            $this->addDBFields($table);
        });
    }

    private function addDBFields($table) {
        foreach($this->model->fields as $field) {
            if(!Schema::hasColumn($this->tableName, $field->formattedTitle)) {
                $this->setTableField($table, $field);
            }
        }
    }

    private function setTableField($table, $field) {
        $tableField = null;
        switch($field->fieldType->name) {
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

    private function getTableName($modelName) {
        return Utils::getFormattedDBName($modelName) . 's';
    }

    public function changeFieldName($oldFieldName, $newFieldName) {
        Schema::table($this->tableName, function (Blueprint $table) use ($oldFieldName, $newFieldName) {
            $table->renameColumn($oldFieldName, $newFieldName);
        });
    }

    public static function changeFieldNameInAllNodeTables($oldFieldName, $newFieldName) {
        $nodeTypes = NodeType::get();
        foreach($nodeTypes as $nodeType) {
            $generator = new self($nodeType);
            $generator->changeFieldName($oldFieldName, $newFieldName);
        }
    }
}