<?php


use App\Models\User;
use App\Models\Webhook;

class UserTest extends TestCase {


    /** @test */
    public function user_can_access_api_by_auth() {
        $loggedInUser = factory(User::class)->create();
        $this->get($this->path())->seeStatusCode(401);
        $this->actingAs($loggedInUser)->get($this->path())->seeStatusCode(200);
    }

    private function path(): string {
        return (new Webhook())->path();
    }
}
