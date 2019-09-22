<?php


use App\Models\User;
use App\Models\Webhook;

/**
 * @group webhook
 */
class WebhookTest extends TestCase {

    /** @test */
    public function there_are_limitation_in_adding_a_webhook_per_user() {
        $user = factory(User::class)->create();
        $user->addToWebhookLimit(5); // give 5 ticket for creating webhook
        $webhookObj = $this->makeWebhook();
        $this->actingAs($user)->post($webhookObj->path(), $webhookObj->toArray())
            ->seeStatusCode(201);

        $userWithoutTicket = factory(User::class)->create(['webhook_limit' => 0]);
        $this->actingAs($userWithoutTicket)->post($webhookObj->path(), $webhookObj->toArray())
            ->seeStatusCode(403);
    }

    /** @test */
    public function webhook_must_have_valid_token() {
        $user = factory(User::class)->create();
        $webhookObj = $this->makeWebhook(null, ['token' => null]);
        $response = $this
            ->actingAs($user)
            ->post($webhookObj->path(), $webhookObj->toArray());
        $response->seeStatusCode(422);
    }

    /** @test */
    public function webhook_must_have_valid_url() {
        $user = factory(User::class)->create();
        $webhookObj = $this->makeWebhook(null, ['url' => null]);
        $response = $this
            ->actingAs($user)
            ->post($webhookObj->path(), $webhookObj->toArray());
        $response->seeStatusCode(422);
    }

    /** @test */
    public function user_can_register_limit_webhook() {
        $this->withOutExceptionHandling();

        //by default user have 100 webhook limitation, override to one
        $user = factory(User::class)->create(['webhook_limit' => 1]);
        $this->assertEquals($user->webhook_limit, 1);
        // use only one remaining limit
        $webhookObj = $this->makeWebhook();
        $this->actingAs($user)
            ->post($webhookObj->path(), $webhookObj->toArray())
            ->seeStatusCode(201);
        //try to add extra than limit
        $anotherWebhook = $this->makeWebhook();
        $this->actingAs($user)
            ->post($anotherWebhook->path(), $anotherWebhook->toArray())
            ->seeStatusCode(403);

    }

    /** @test */
    public function after_registering_new_webhook_user_webhook_store_must_be_filled_by() {
    }

    /** @test */
    public function same_user_webhook_must_not_save_twice() {
    }

    /** @test */
    public function user_can_see_own_created_webhooks() {
    }

    /** @test */
    public function user_can_keep_track_of_webhooks_logs() {
    }

    /** @test */
    public function user_cannot_see_other_users_webhooks() {
    }

    /** @test */
    public function user_can_test_webhook_by_payload() {
    }

    private function makeWebhook(int $count = null, array $override = []) {
        if (!isset($count)) {
            return factory(Webhook::class)->make($override);
        }
        return factory(Webhook::class, $count)->make($override);
    }
}
