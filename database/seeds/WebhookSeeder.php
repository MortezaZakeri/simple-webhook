<?php

use App\Models\User;
use App\Models\Webhook;
use Illuminate\Database\Seeder;

class WebhookSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $users = User::all();
        foreach ($users as $user) {
            $webhooks = factory(Webhook::class,20)->make(['user_id' => $user->id])->toArray();
            Webhook::insert($webhooks);
        }

    }
}
