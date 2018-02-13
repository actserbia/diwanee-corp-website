<?php

namespace App\Models\Node;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Constants\FieldType;
use App\Utils\Utils;

class NodeModelDBGenerator {
    private $model = null;
    private $tableName = null;
    private $oldTableName = null;

    public function __construct($model, $oldNodeTypeName) {
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
            $this->createDBTable();
        } else {
            $this->updateDBTable();
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
            if(!Schema::hasColumn($this->tableName, $field->formattedName)) {
                $this->setTableField($table, $field);
            }
        }
    }

    private function setTableField($table, $field) {
        $tableField = null;
        switch($field->fieldType->name) {
            case FieldType::Text:
                $tableField = $table->string($field->formattedName, 255);
                break;

            case FieldType::Integer:
                $tableField = $table->unsignedInteger($field->formattedName);
                break;

            case FieldType::Date:
                $tableField = $table->timestamp($field->formattedName);
                break;
        }

        if(!$field->pivot->required) {
            $tableField->nullable();
        }
    }

    private function getTableName($modelName) {
        return Utils::getFormattedDBName($modelName) . 's';
    }
}