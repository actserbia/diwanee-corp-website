<?php

return [
    'UserRole' => [
        'user' => 'User',
        'brand' => 'Brand',
        'moderator' => 'Moderator',
        'admin' => 'Admin'
    ],
    'TypeStatus' => [
        '1' => 'Published',
        '0' => 'Unpublished',
        '4' => 'Deleted'
    ],
    'NodeStatus' => [
        '1' => 'Published',
        '0' => 'Unpublished',
        '4' => 'Deleted'
    ],
    'FieldType' => [
        'integer' => 'Integer',
        'text' => 'Text',
        'date' => 'Date'
    ],
    'ElementType' => [
        'text' => 'Text',
        'slider_image' => 'Slider Image',
        'diwanee_image' => 'Image',
        'diwanee_video' => 'Video',
        'list' => 'List',
        'heading' => 'Heading',
        'quote' => 'Quote'
    ],
    'UserActive' => [
        '1' => 'Active',
        '0' => 'Not Active'
    ],
    'NodeList' => [
        '1' => 'Accending',
        '0' => 'Descending'
    ],
    'Filters' => [
        'equal' => 'Equal',
        'like' => 'Contains',
        'empty_or_null' => 'Empty',
        'greater' => 'Greater',
        'greater_or_equal' => 'Greater or equal',
        'not_equal' => 'Not Equal',
        'not_like' => 'Not Contains',
        'not_empty_or_null' => 'Not Empty',
        'less_or_equal' => 'Less or equal',
        'less' => 'Less',

        'and' => 'And',
        'or' => 'Or'
    ]
];
