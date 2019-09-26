<?php


use App\Models\User;
use App\Models\Webhook;
use App\Models\WebhookLog;

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
    public function user_registered_webhook_must_be_see_in_table() {
        $user = factory(User::class)->create();
        $webhookObj = $this->makeWebhook();
        $this->actingAs($user)
            ->post($webhookObj->path(), $webhookObj->toArray());
        $this->seeInDatabase('webhooks', [
            'user_id' => $user->id,
            'token' => $webhookObj->token,
            'url' => $webhookObj->url
        ]);
    }

    /** @test */
    public function same_user_webhook_must_not_save_twice() {
        $user = factory(User::class)->create();
        $webhookObj = $this->makeWebhook();
        $this->actingAs($user)->post($webhookObj->path(), $webhookObj->toArray());
        $this->assertEquals($user->webhooks()->count(), 1);
        //same webhook again
        $this->actingAs($user)->post($webhookObj->path(), $webhookObj->toArray());
        //still 1
        $this->assertEquals($user->webhooks()->count(), 1);
    }

    /** @test */
    public function user_can_see_own_created_webhooks() {

        $user = factory(User::class)->create();
        //assign 100 webhook to user
        $webhooks = $this->createWebhook(100, ['user_id' => $user->id]);
        $response = $this->actingAs($user)->get($webhooks[0]->path());

        $response->seeStatusCode(200);
        $content = $this->getContent($response);

        $this->assertNotNull($content->data);
        $this->assertEquals($content->extra->count, 100);
        $this->assertEquals($user->webhooks()->count(), 100);

    }

    /** @test */
    public function when_event_happened_related_webhooks_get_some_logs() {
        $this->withoutExceptionHandling();
        //register a webhook for user
        $user = factory(User::class)->create();
        $webhookObj = $this->actingAs($user)->createWebhook(null, [
            'user_id' => $user->id,
            'url' => 'http://localhost:8081/test'
        ]);

        //fire an event
        $this->actingAs($user)
            ->get($this->path() . "/call/{$webhookObj->id}")->seeStatusCode(200);
        //dd($webhookObj->id);
        // see log in table
        $this->seeInDatabase('webhook_logs', [
            'webhook_id' => $webhookObj->id
        ]);
    }

    /** @test */
    public function user_can_keep_track_of_webhooks_logs() {
        $john = factory(User::class)->create();
        $this->createWebhook(null, ['user_id' => $john->id]);

        $response = $this->actingAs($john)->get($this->path())
            ->seeStatusCode(200);
        $this->assertNotEmpty($this->getContent($response)->data);

    }

    /** @test */
    public function user_cannot_see_other_users_webhooks() {
        $john = factory(User::class)->create();
        $mike = factory(User::class)->create();
        //john create a webhook
        $this->createWebhook(null, ['user_id' => $john->id]);
        //mike cannot see any webhook
        $response = $this->actingAs($mike)
            ->get($this->path())
            ->seeStatusCode(200);
        $this->assertEmpty($this->getContent($response)->data);
    }


    //make object for webhooks not persist
    private function makeWebhook(int $count = null, array $override = []) {
        if (!isset($count)) {
            return factory(Webhook::class)->make($override);
        }
        return factory(Webhook::class, $count)->make($override);
    }

    //persist in storage
    private function createWebhook(int $count = null, array $override = []) {
        if (!isset($count)) {
            return factory(Webhook::class)->create($override);
        }
        return factory(Webhook::class, $count)->create($override);
    }

    private function path(): string {
        return (new Webhook())->path();
    }
}
