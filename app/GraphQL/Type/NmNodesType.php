<?php
namespace App\GraphQL\Type;

use Illuminate\Support\Str;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use App\Utils\Utils;
use App\GraphQL\Type\Scalar\Timestamp;

class NmNodesType extends GraphQLType {
    protected $modelName = '';

    protected $fields = [];

    public function __construct($attributes = array()) {
        parent::__construct($attributes);

        $this->attributes['name'] = Str::plural($this->modelName);
        $this->attributes['description'] = Str::plural($this->modelName) . ' type';
        $this->attributes['model'] = 'App\\NodeModel\\' . $this->modelName;
    }

    public function fields() {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::int())
            ],
            'node_id' => [
                'type' => Type::nonNull(Type::int())
            ]
        ];

        foreach($this->fields as $fieldName => $fieldSettings) {
            $type = $fieldSettings['type'][0]::$fieldSettings['type'][1];
            $fields[$fieldName] = [
                'type' => (isset($fieldSettings['required']) && $fieldSettings['required']) ? Type::nonNull($type) : $type,
                'description' => Str::studly($fieldName)
            ];
        }
        return $fields;
    }
}