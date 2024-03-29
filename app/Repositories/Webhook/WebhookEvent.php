<?php

use Illuminate\Database\Eloquent\Collection;

/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Repositories\Webhook;

use App\Models\Webhook;
use Exception;
use App\Repositories\AppRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\EachPromise;
use GuzzleHttp\Psr7\Response;


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

    /*  Maximum time to wait for endpoint in seconds , default in config 10s*/
    public $timeout;

    /** @var string , expected request type by endpoint default JSON , can override */
    public $responseType;

    /** @var string , HTTP verb */
    public $verb;

    /** @var string , client side token ,stored in webhooks model */
    public $token;

    /** @var anytype , payload send to endpoint */
    public $payload = [];

    /* @var array , in case that client needs specific headers */
    public $headers = [];

    /** @var Guzzle Http Response | null */
    private $response;

    /* @var Collection , list of all webhooks include token and urls */
    public $endpoints = [];

    /* @var int , number of concurrence request , default is 10 in config file */
    public $concurrency;

    public function handle(): array {
        // guzzle request failed afterr
        $status = [
            'succeed' => 0,
            'failed' => 0
        ];

        try {
            // guzzle http client
            $client = app(Client::class);
            $promises = (function () use ($client) {

                foreach ($this->endpoints as $endpoint) {
                    $data = $this->makeDataOption($endpoint);
                    yield $client
                        ->requestAsync($endpoint->verb, $endpoint->url, $data)
                        ->then(function (Response $response) use ($endpoint) {
                            $response->webhook = $endpoint->toArray();
                            $this->logResponse(
                                $response->webhook['id'],
                                $response->getStatusCode(),
                                $response->getBody()->getContents(),
                                'SUCCEED');

                        })->otherwise(function (Exception $reject) use ($endpoint) {
                            $reject->webhook = $endpoint->toArray();

                            $this->logResponse(
                                $reject->webhook['id'],
                                $reject->getCode(),
                                $reject->getMessage(),
                                'FAILED');
                        });
                }
            })();

            $promisesResult = new EachPromise($promises, [
                'concurrency' => $this->concurrency,
                'fulfilled' => function ($response, $pending, $result) use ($status, $promises) {
                    $status['succeed']++;
                },
                'rejected' => function ($response, $pending, $result) use ($status, $promises) {
                    $status['failed']++;
                },

            ]);
            $promisesResult->promise()->wait();

        } catch (Exception $exception) {
            $this->handleException(__METHOD__, $exception, "Cannot call end point $this->url", 5);
        }
        return $status;
    }

    private function makeDataOption(Webhook $endpoint): array {
        return [
            'timeout' => $this->timeout,
            'body' => json_encode($this->payload),
            'verify' => $this->ssl,
            'headers' => $this->headers,
            'token' => $endpoint->token,
            'id' => $endpoint->id
        ];
    }

    private function logResponse($webhookId, $code, string $body, string $status) {

        (new WebhookLogRepository())->log($webhookId, $code, $body, $status);
    }

}
