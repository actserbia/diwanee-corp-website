<?php

return [
    'articles' => [
        'not_exists' => 'The article with id <strong>:id</strong> doesn\'t exist.',
        'store_success' => 'The article <strong>:title</strong> has successfully been created.',
        'store_error' => 'The article <strong>:title</strong> has not successfully been created.',
        'update_success' => 'The article <strong>:title</strong> has successfully been updated.',
        'update_error' => 'The article <strong>:title</strong> has not successfully been updated.',
        'destroy_success' => 'The article <strong>:title</strong> has successfully been archived.',
        'destroy_error' => 'The article <strong>:title</strong> has not successfully been archived.'
    ],
    'tags' => [
        'not_exists' => 'The tag with id <strong>:id</strong> doesn\'t exist.',
        'store_success' => 'The tag <strong>:name</strong> has successfully been created.',
        'store_error' => 'The tag <strong>:name</strong> has not successfully been created.',
        'update_success' => 'The tag <strong>:name</strong> has successfully been updated.',
        'update_error' => 'The tag <strong>:name</strong> has not successfully been updated.',
        'destroy_success' => 'The tag <strong>:name</strong> has successfully been archived.',
        'destroy_error' => 'The tag <strong>:name</strong> has not successfully been archived.'
    ],
    'users' => [
        'not_exists' => 'The user with id <strong>:id</strong> doesn\'t exist.',
        'store_success' => 'The user <strong>:name</strong> has successfully been created.',
        'store_error' => 'The user <strong>:name</strong> has not successfully been created.',
        'update_success' => 'The user <strong>:name</strong> has successfully been updated.',
        'update_error' => 'The user <strong>:name</strong> has not successfully been updated.',
        'destroy_success' => 'The user <strong>:name</strong> has successfully been archived.',
        'destroy_error' => 'The user <strong>:name</strong> has not successfully been archived.'
    ],
    'check_sir_trevor_content' => [
        'data_missing' => 'It should be array with data key.',
        'type_or_data_missing' => 'Element :elementIndex type or data not set.',
        'type_not_valid' => ':type is not valid sir trevor element type. Valid types are: :validTypes',
        'data_param_missing' => 'Element :elementIndex :param is not set.',
        'format_not_valid' => 'Element :elementIndex format is not set or is not valid.',
        'image_data_missing' => 'Element :elementIndex image data is not set.',
        'video_data_missing' => 'Element :elementIndex video data (remote_id or source) not set.',
        'list_items_not_valid' => 'Element :elementIndex listItems is not set or is not array.',
        'list_item_content_missing' => 'Element :elementIndex list item :itemIndex content is not set!',
        'not_valid_message' => 'Content is not in valid sir trevor format. :message',
    ],
    'templates' => [
        'global' => [
            'back' => 'Back',
            'id' => 'Id',
            'created' => 'Created',
            'actions' => 'Actions',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'create_new' => 'Create new',
            'delete_title' => 'Confirm delete record',
            'delete_question' => 'Are you sure you want to delete :title',
            'delete_confirm_message' => 'Yes I\'m sure. Delete'
        ],
        'articles' => [
            'title' => 'Title',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'meta_keywords' => 'Meta Keywords',
            'content_description' => 'Content description',
            'external_url' => 'External url',
            'publication' => 'Publication',
            'brand' => 'Brand',
            'influencer' => 'Influencer',
            'category' => 'Category',
            'subcategories' => 'Subcategories',
            'status' => 'Status',
            'content' => 'Content',
            'list_title' => 'Articles',
            'author' => 'Author',
            'create_article_title' => 'Create article',
            'create_article_button_text' => 'Create article',
            'edit_article_title' => 'Edit article',
            'edit_article_button_text' => 'Save article changes'
        ],
        'tags' => [
            'name' => 'Name',
            'type' => 'Type',
            'parents' => 'Parents',
            'children' => 'Children',
            'list_title' => 'Categories',
            'create_tag_title' => 'Create category',
            'create_tag_button_text' => 'Create category',
            'edit_tag_title' => 'Edit category',
            'edit_tag_button_text' => 'Save category changes'
        ],
        'users' => [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'confirm_password' => 'Confirm password',
            'role' => 'Role',
            'list_title' => 'Users',
            'create_user_title' => 'Create user',
            'create_user_button_text' => 'Create user',
            'edit_user_title' => 'Edit user',
            'edit_user_button_text' => 'Save user changes'
        ]
    ]
];
