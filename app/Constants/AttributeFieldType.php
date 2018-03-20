<?php
namespace App\Constants;

final class AttributeFieldType {
    use Constants;
    
    const Text = 'Text';
    const Integer = 'Integer';
    const Date = 'Date';
    const Boolean = 'Boolean';
    const Json = 'Json';
    
    
    const modelAttributeTypes = array(
        self::Text => 'Models::AttributeType_Text',
        self::Integer => 'Models::AttributeType_Number',
        self::Date => 'Models::AttributeType_Date',
        self::Boolean => 'Models::AttributeType_Boolean',
        self::Json => 'Models::AttributeType_Json'
    );
    
    const databaseTypes = array(
        self::Text => ['string', 255],
        self::Integer => 'unsignedInteger',
        self::Date => 'timestamp',
        self::Boolean => 'boolean',
        self::Json => 'longText'
    );
    
    const graphQLTypes = array(
        self::Text => ['Type', 'string'],
        self::Integer => ['Type', 'int'],
        self::Date => ['Timestamp', 'type'],
        self::Boolean => ['Type', 'boolean'],
        self::Json => ['JsonData', 'type']
    );
}