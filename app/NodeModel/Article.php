<?php
namespace App\NodeModel;

use App\AppModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\Models;

class Article extends AppModel {
use SoftDeletes;
protected $fillable = ['meta_title', 'meta_description', 'external_url'];
protected $allFields = ['id', 'created_at', 'updated_at', 'deleted_at', 'meta_title', 'meta_description', 'external_url'];
protected $requiredFields = [''];
protected $defaultFieldsValues = [''];
protected $attributeType = [
];
}
