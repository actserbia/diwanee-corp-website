<?php
    namespace App\NodeModel;

    use App\AppModel;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use App\Constants\Models;

    class ArticleTest extends AppModel {
        use SoftDeletes;

        protected $fillable = ['meta_title'];

        protected $allFields = ['id', 'created_at', 'updated_at', 'deleted_at', 'meta_title'];

        protected $requiredFields = [''];

        protected $defaultFieldsValues = [''];

        protected $attributeType = [
            'meta_title' => Models::AttributeType_Text,
        ];

    }
