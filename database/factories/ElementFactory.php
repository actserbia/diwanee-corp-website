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
    $types = [ElementType::Text, ElementType::Image, ElementType::Slider];
    
    $type = $types[$faker->numberBetween(0, count($types) - 1)];
    $content = '';
    $options = array();
    switch($type) {
        case ElementType::Text:
            $content = $faker->paragraph;
            break;
          
        case ElementType::Image:
            $content = 'test.jpg';
            $options['alt'] = $faker->text(30);
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


$factory->defineAs(Element::class, 'image', function (Faker $faker) {
    $options = array();
    $options['alt'] = $faker->text(30);

    return [
        'type' => 'image',
        'content' => 'test.jpg',
        'options' => json_encode($options)
    ];
});