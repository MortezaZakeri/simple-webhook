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
        $webhooks = [];
        foreach ($users as $user) {
            $webhooks[] = factory(Webhook::class)->make(['user_id' => $user->id])->toArray();
        }

        try {
            Webhook::insert($webhooks);
        } catch (Exception $exception) {
        }
    }
}
