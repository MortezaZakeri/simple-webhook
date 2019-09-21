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

use App\Models\User;

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => hash('MD5', 'test'),
        'webhook_limit' => 100,
        'permission_id' => random_int(1, 2), // user=1 , admin =2
    ];
});
