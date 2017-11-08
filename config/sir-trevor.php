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
                'partner_id' => '676152',
                'uiconf_id' => '37639151',
                'player_id' => '7503092'
            ]
        ]
    ]
];