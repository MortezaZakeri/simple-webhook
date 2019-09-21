<?php
/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Repositories\Webhook;

use Exception;
use App\Repositories\AppRepository;
use GuzzleHttp\Client;

class WebhookEvent extends AppRepository {


    /**
     * @var string ,webhook url
     * stored in webhooks model
     */
    public $url;

    /* @var bool set guzzle verifySsl flag default false, configurable in webhook.php */
    public $ssl;

    /**
     * @var int ,maximum number of try for a webhook call
     * default value is 3, configurable by webhook.php in root config folder, can override
     */
    public $numberOfTry;

    /** @var string , expected request type by endpoint default JSON , can override */
    public $responseType;

    /** @var string , HTTP verb */
    public $method;

    /** @var string , client side token ,stored in webhooks model */
    public $token;

    /** @var anytype , payload send to endpoint */
    public $payload = [];

    /* @var array , in case that client needs specific headers */
    public $headers = [];

    /** @var Guzzle Http Response | null */
    private $response;

    public function handle() {
        // guzzle request failed afterr
        $timeOut = 10;
        try {
            // guzzle http client
            $client = app(Client::class);
            $this->response = $client->request($this->method, $this->url, [
                'timeout' => $timeOut,
                'body' => json_encode($this->payload),
                'verify' => $this->ssl,
                'headers' => $this->headers,
            ]);

        } catch (Exception $exception) {
            $this->handleException(__METHOD__, $exception, "Cannot call end point $this->url", 5);
        }
    }

}
