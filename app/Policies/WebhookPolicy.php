<?php
/**
 * User: MB
 * Date: 9/22/2019
 */

namespace App\Policies;


use App\Models\User;

class WebhookPolicy {


    public function newWebhook(User $user, int $count) :bool {
        return $user->canHaveWebhook();
    }
}
