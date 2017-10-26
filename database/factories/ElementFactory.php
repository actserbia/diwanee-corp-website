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
    $types = [ElementType::Text, ElementType::DiwaneeImage, ElementType::SliderImage, ElementType::Video, ElementType::Heading, ElementType::ElementList];
    
    $type = $types[$faker->numberBetween(0, count($types) - 1)];
    $content = array();
    switch($type) {
        case ElementType::Text:
        case ElementType::Heading:
            $content['text'] = $faker->paragraph;
            $content['format'] = 'html';
            break;
          
        case ElementType::DiwaneeImage:
        case ElementType::SliderImage:
            $content['file']['url'] = 'test.jpg';
            $content['seoname'] = $faker->text(30);
            $content['seoalt'] = $faker->text(30);
            $content['caption'] = $faker->text(30);
            $content['copyright'] = $faker->text(30);
            break; 
          
        case ElementType::Video:
            $content['remote_id'] = 'FKUAAZSJiGY';
            $content['source'] = 'youtube';
            break; 

        case ElementType::ElementList:
            $count = $faker->numberBetween(1, 5);
            for($index = 0; $index < $count; $index++) {
                $content['listItems'][] = array('content' => $faker->text(20));
            }
            $content['format'] = 'html';
            break;
    }
    
    return [
        'type' => $type,
        'content' => json_encode($content)
    ];
});


$factory->defineAs(Element::class, ElementType::SliderImage, function (Faker $faker) {
    $content = array();
    $content['file']['url'] = 'test.jpg';
    $content['alt'] = $faker->text(30);
    $content['seoname'] = $faker->text(30);
    $content['seoalt'] = $faker->text(30);
    $content['caption'] = $faker->text(30);
    $content['copyright'] = $faker->text(30);

    return [
        'type' => ElementType::SliderImage,
        'content' => json_encode($content)
    ];
});