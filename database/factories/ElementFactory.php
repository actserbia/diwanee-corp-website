<?php

use Faker\Generator as Faker;
use App\Element;
use App\Constants\ElementType;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Element::class, function (Faker $faker) {
    $types = [ElementType::Text, ElementType::Image];
    
    $type = $types[$faker->numberBetween(0, count($types) - 1)];
    $content = '';
    $options = array();
    switch($type) {
        case ElementType::Text:
            $content = $faker->paragraph;
            break;
          
        case ElementType::Image:
            $content = 'test.jpg';
            break; 
          
        //case ElementType::Video:
        //    $content = 'FKUAAZSJiGY';
        //    break; 
    }
    
    return [
        'type' => $type,
        'content' => $content,
        'options' => json_encode($options)
    ];
});
