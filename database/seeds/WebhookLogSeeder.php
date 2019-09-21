<?php

use App\Models\WebhookLog;
use Illuminate\Database\Seeder;

class WebhookLogSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $logs = factory(WebhookLog::class, 1000)->make();
        try {
            WebhookLog::insert($logs->toArray());
        } catch (Exception $exception) {

        }


    }
}
