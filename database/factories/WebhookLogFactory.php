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


use App\Models\WebhookLog;


$factory->define(WebhookLog::class, function (Faker\Generator $faker) {
    return [
        'webhook_id' => random_int(1, 3),
        'status_code' => $faker->randomKey([200, 400, 404, 500]),
        'message' => $faker->text
    ];
});

