<?php

return [
    'thumborServer' => env('THUMBOR_SERVER', null),
    'thumborSecret' => env('THUMBOR_SECRET', null),
    'imagesUrl' => env('IMAGES_URL', null),
    'imagesFolder' => env('IMAGES_FOLDER', null),
    's3amazon' => [
        'amazonBucket' => env('AMAZON_BUCKET', null),
        'amazonPath' => env('AMAZON_PATH', null),
        'amazonAccessKey' => env('AMAZON_ACCESS_KEY', null),
        'amazonSecretKey' => env('AMAZON_SECRET_KEY', null),
        'amazonImagesFolder' => 'diwanee_corp_images'
    ]
];
