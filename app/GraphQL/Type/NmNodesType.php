<?php
namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use App\Utils\Utils;
use App\GraphQL\Type\Scalar\Timestamp;

class NmNodesType extends GraphQLType {
    protected $modelName = '';

    protected $fields = [];

    public function __construct($attributes = array()) {
        parent::__construct($attributes);

        $this->attributes['name'] = $this->modelName . 's';
        $this->attributes['description'] = $this->modelName . 's type';
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
            $typeName = $fieldSettings['type'];
            $type = ($typeName === 'date') ? Timestamp::type() : Type::$typeName();
            $fields[$fieldName] = [
                'type' => (isset($fieldSettings['required']) && $fieldSettings['required']) ? Type::nonNull($type) : $type,
                'description' => Utils::getFormattedName($fieldName)
            ];
        }
        return $fields;
    }
}