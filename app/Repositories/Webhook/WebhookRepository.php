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
     * @param string $verb
     * @return Webhook|null
     */
    public function create(User $user, string $url, string $token, string $verb = 'POST'): ?Webhook {

        try {
            return Webhook::updateOrCreate(
                ['url' => $url, 'user_id' => $user->id, 'verb' => $verb],
                ['url' => $url, 'user_id' => $user->id, 'verb' => $verb, 'token' => $token]
            );
        } catch (QueryException $exception) {
            $this->handleException(__FUNCTION__, $exception, 'Cannot register a new webhook', 4);
        }
        return null;
    }

    public function get(?User $user,? int $id): array {
        if (!isset($user)) {
            return [];
        }
        $query = $user->webhooks();
        if ($id > 0) {
            $query = $query->where('id', $id);
        }
        try {
            return $query->get()->toArray();
        } catch (QueryException $exception) {
            $this->handleException($exception, __FUNCTION__, "Cannot fetch $user->id webhooks", 3);
            return [];
        }

    }


}
