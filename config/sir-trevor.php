<?php
return [
    'uploadUrl' => '/sirtrevor/upload-image',

    'iconUrl' => '/pictures/sir-trevor-icons.svg',

    'editorClass' => '.sir-trevor',

    'defaultType' => 'Text',

    'blockTypes' => [ 'Text', 'Heading', 'Quote', 'List', 'DiwaneeImage', 'DiwaneeVideo', 'SliderImage' ],

    'stylesheets' => [
        'css/sir-trevor.css'
    ],

    'scripts' => [
        'js/sir-trevor.js'
    ],

    'videos' => [
        'providers' => [
            'kaltura' => [
                'partner_id' => env('KALTURA_PARTNER_ID', null),
                'uiconf_id' => env('KALTURA_UICONF_ID', null),
                'player_id' => env('KALTURA_PLAYER_ID', null)
            ]
        ]
    ]
];