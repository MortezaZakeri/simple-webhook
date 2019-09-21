<?php
/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Repositories\Webhook;

use App\Models\User;
use App\Models\Webhook;
use App\Repositories\AppRepository;
use Illuminate\Database\QueryException;


class WebhookRepository extends AppRepository {


    /**
     * desc
     * @param User $user
     * @param string $url
     * @param string $token
     * @param string $method
     * @return Webhook|null
     */
    public function create(User $user, string $url, string $token, string $method = 'POST'): ?Webhook {

        try {
            Webhook::updateOrCreate(
                ['url' => $url, 'user_id' => $user->id, 'verb' => $method],
                ['url' => $url, 'user_id' => $user->id, 'verb' => $method, 'token' => $token]
            );
        } catch (QueryException $exception) {
            $this->handleException(__FUNCTION__, $exception, 'Cannot register a new webhook', 4);
        }
    }


}
