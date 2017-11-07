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
        'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.min.js',
        'js/sir-trevor.js'
    ],
  
    'videos' => [
        'providers' => [
            'kaltura' => [
                'partner_id' => '676152',
                'uiconf_id' => '37639151',
                'player_id' => '7503092'
            ]
        ]
    ]
];