<?php
/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Repositories\Webhook;


use App\Models\WebhookLog;
use App\Repositories\AppRepository;
use Illuminate\Database\QueryException;

class WebhookLogRepository extends AppRepository {

    public function log(int $webhookId, $code, string $body = '', string $status = '') {

        try {
            WebhookLog::create([
                'webhook_id' => $webhookId,
                'status_code' => $code,
                'message' => $body,
                'status' => $status,
            ]);
        } catch (QueryException $exception) {

            $this->handleException($exception, __FUNCTION__,
                "Cannot log webhook $webhookId response ", false);
        }
    }

}
