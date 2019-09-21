<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $users = factory(User::class, 50)->make();
        try {
            User::insert($users->toArray());
        }
        catch (Exception $exception) {

        }

    }
}
