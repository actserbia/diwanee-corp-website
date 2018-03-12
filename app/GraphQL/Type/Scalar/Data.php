<?php

namespace App\GraphQL\Type\Scalar;

use GraphQL\Type\Definition\StringType;
use GraphQL\Utils\Utils;

class Data extends StringType
{
    private static $_instance = null;
    const MIN_INT = 0;
    /**
     * @var string
     */
    public $name = "Data";

    /**
     * @var string
     */
    public $description = "Encoded JSON data";

    protected function __clone() {}

    static public function type() {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        parent::__construct();
        Utils::invariant($this->name, 'Type must be named.');
    }

    public function serialize($value) {
        return $this->toData($value);
    }

    public function parseValue($value) {
        return $this->toData($value);
    }

    protected function toData($value) {
        return $value;
        
        //if (is_string($value)) {
        //    return $value;
        //}
        //return json_encode($value);
    }
}