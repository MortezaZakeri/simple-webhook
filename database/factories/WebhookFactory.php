<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


use App\Models\Webhook;


$factory->define(Webhook::class, function (Faker\Generator $faker) {
    return [
        'token' => $faker->text,
        'url' => $faker->url,
        'verb' => $faker->randomElement(['POST', 'GET']),
        'user_id' => $faker->uuid,
    ];
});
