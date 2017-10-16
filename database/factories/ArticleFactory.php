<?php

use Faker\Generator as Faker;
use App\Article;

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

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->text(30),
        'meta_title' => $faker->text(30),
        'meta_description' => $faker->text(30),
        'meta_keywords' => $faker->text(30),
        'content_description' => $faker->text(30),
        'status' => $faker->numberBetween(0, 1),
        'id_author' => 1
    ];
});
