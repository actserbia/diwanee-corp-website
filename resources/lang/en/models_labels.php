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
        'nodes_label' => 'Articles'
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
        'tag_type_label' => 'Type',
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
        'attributes_fields_label' => 'Fields',
        'tags_fields_label' => 'Tags',
        'sir_trevor__fields_label' => 'Sir Trevor Fields'
    ],
  
    'Field' => [
        'label_single' => 'field',
        'label_plural' => 'Fields',
        'id' => 'Id',
        'title' => 'Title',
        'field_type_label' => 'Type',
        'attribute_field_type_label' => 'Type',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
        'deleted_at' => 'Deleted Date',
        'active' => 'Active',
        'required' => 'Required',
        'multiple' => 'Multiple',
        'multiple_list' => 'Multiple'
    ],
  
    'NodeList' => [
        'label_single' => 'List',
        'label_plural' => 'Lists',
        'id' => 'Id',
        'name' => 'Name',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
        'deleted_at' => 'Deleted Date'
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
        'additional_data_label' => '',
        'model_type_label' => 'Type',
        'content' => 'Content',
        'author_label' => 'Author'
    ],
  
    'Element' => [
        'id' => 'Id',
        'id_element' => 'Element Id',
        'type' => 'Type',
        'data' => [
            'text' => 'Text',
            'heading' => 'Heading',
            'heading_h1' => 'Heading H1',
            'heading_h2' => 'Heading H2',
            'heading_h3' => 'Heading H3',
            'heading_h4' => 'Heading H4',
            'heading_h5' => 'Heading H5',
            'heading_type' => 'Heading Type',
            'quote' => 'Quote',
            'cite' => 'Quote Credit',
            'list' => 'List',
            'seoname' => 'Image Seoname',
            'seoalt' => 'Image Seoalt',
            'caption' => 'Image Caption',
            'copyright' => 'Image Copyright',
            'hash' => 'Image Hash',
            'source' => 'Video Provider',
            'remote_id' => 'Video Remote Id'
        ],
        'data_label' => 'Data',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
        'nodes_label' => 'Node'
    ],
];
