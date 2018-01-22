<?php

return [
    'global' => [
        'confirm' => 'Confirm'
    ],
  
    'User' => [
        'id' => 'Id',
        'name' => 'Name',
        'email' => 'E-Mail Address',
        'password' => 'Password',
        'role' => 'Role',
        'active' => 'Active',
        'api_token' => 'Api Token',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
        'deleted_at' => 'Deleted Date',
        'articles_label' => 'Articles',
        'articles_count' => 'Articles Count'
    ],
  
    'TagType' => [
        'id' => 'Id',
        'name' => 'Name',
        'subtype_label' => 'Subtype',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
        'deleted_at' => 'Deleted Date'
    ],
  
    'Tag' => [
        'id' => 'Id',
        'tagType_label' => 'Type',
        'name' => 'Name',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
        'deleted_at' => 'Deleted Date',
        'parents_label' => 'Parents',
        'parents_count' => 'Parents Count',
        'children_label' => 'Children',
        'children_count' => 'Children Count'
    ],
];
