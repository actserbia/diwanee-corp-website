<?php

namespace App\Models\Node;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Constants\FieldType;
use App\Utils\Utils;

class NodeModelDBGenerator {
    private $model = null;
    private $isNew = true;
    private $oldNodeTypeName = null;

    public function __construct($model, $isNew, $oldNodeTypeName) {
        $this->model = $model;
        $this->isNew = $isNew;
        $this->oldNodeTypeName = $oldNodeTypeName;
    }

    public function generate() {
        if($this->isNew) {
            $this->createDBTable();
        } else {
            $this->updateDBTable();
        }
    }

    private function createDBTable() {
        Schema::create($this->getTableName($this->model->name), function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_id');

            $this->addDBFields($table);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('node_id')->references('id')->on('nodes');
        });
    }

    private function updateDBTable() {
        $oldTableName = $this->getTableName($this->oldNodeTypeName);
        $newTableName = $this->getTableName($this->model->name);

        if($oldTableName !== $newTableName) {
            Schema::rename($oldTableName, $newTableName);
        }

        Schema::table($newTableName, function (Blueprint $table) {
            $this->addDBFields($table);
        });
    }

    private function addDBFields($table) {
        foreach($this->model->fields as $field) {
            if(!Schema::hasColumn($this->getTableName($this->model->name), $field->formattedName)) {
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