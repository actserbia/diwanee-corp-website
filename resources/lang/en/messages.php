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
        'store_success' => 'The tag <strong>:title</strong> has successfully been created.',
        'store_error' => 'The tag <strong>:title</strong> has not successfully been created.',
        'update_success' => 'The tag <strong>:title</strong> has successfully been updated.',
        'update_error' => 'The tag <strong>:title</strong> has not successfully been updated.',
        'destroy_success' => 'The tag <strong>:title</strong> has successfully been archived.',
        'destroy_error' => 'The tag <strong>:title</strong> has not successfully been archived.'
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
    ]
];
