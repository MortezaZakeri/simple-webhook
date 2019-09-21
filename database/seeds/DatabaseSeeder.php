<?php


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $env = getenv('APP_ENV');

        if ($env == 'local' || $env == 'testing') {
            $this->call(UserSeeder::class);
            $this->call(WebhookSeeder::class);
            $this->call(WebhookLogSeeder::class);
        }
    }
}
