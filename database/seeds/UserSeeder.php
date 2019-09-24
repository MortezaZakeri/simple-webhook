<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *  'name',
     * 'email',
     * 'password',
     * 'webhook_limit',
     * 'permission_id',
     * @return void
     */
    public function run() {
        $faker = Faker::create();
        $users = [
            [
                'id' => $faker->uuid,
                'name' => 'MZ',
                'email' => 'mz@test.com',
                'password' => 'test123',
                'webhook_limit' => 100,
                'permission_id' => 1
            ],
            [
                'id' => $faker->uuid,
                'name' => 'Peakon',
                'email' => 'pk@test.com',
                'password' => 'test123',
                'webhook_limit' => 100,
                'permission_id' => 1
            ],
            [
                'id' => $faker->uuid,
                'name' => 'Administrator user',
                'email' => 'admin@test.com',
                'password' => 'test123',
                'webhook_limit' => 200,
                'permission_id' => 2
            ],
        ];
        try {
            User::insert($users);
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

    }
}
