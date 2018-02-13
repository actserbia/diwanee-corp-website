<?php

return [
    'global' => [
        'confirm' => 'Confirm'
    ],
  
    'User' => [
        'label_single' => 'user',
        'label_plural' => 'Users',
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
  
    'FieldType' => [
        'label_single' => 'field type',
        'label_plural' => 'Field types',
        'id' => 'Id',
        'name' => 'Name',
        'subtype_label' => 'Subtype',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
        'deleted_at' => 'Deleted Date'
    ],
  
    'TagType' => [
        'label_single' => 'tag type',
        'label_plural' => 'Tag types'
    ],
  
    'Tag' => [
        'label_single' => 'tag',
        'label_plural' => 'Tags',
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

    'NodeType' => [
        'label_single' => 'type',
        'label_plural' => 'Types',
        'id' => 'Id',
        'name' => 'Name',
        'status' => 'Status',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
        'deleted_at' => 'Deleted Date',
        'fields_label' => 'Fields',
        'tags_label' => 'Tags',
        'sirTrevor_label' => 'Sir Trevor Fields'
    ],
  
    'Field' => [
        'label_single' => 'field',
        'label_plural' => 'Fields',
        'id' => 'Id',
        'title' => 'Title',
        'fieldType_label' => 'Type',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
        'deleted_at' => 'Deleted Date',
        'active' => 'Active',
        'required' => 'Required',
        'multiple' => 'Multiple',
        'sortable' => 'Sortable'
    ],
  
    'Node' => [
        'label_single' => 'node',
        'label_plural' => 'Nodes',
        'id' => 'Id',
        'title' => 'Title',
        'status' => 'Status',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
        'deleted_at' => 'Deleted Date',
        'nodeType_label' => 'Type',
        'content' => 'Content'
    ]
];
