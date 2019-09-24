<?php
/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Repositories\Webhook;

use App\Models\User;
use App\Models\Webhook;
use App\Repositories\AppRepository;
use Exception;

class WebhookCall extends AppRepository {


    private $webhook;

    public function __construct() {
        $this->webhook = new WebhookEvent();
    }


    public static function make(): self {

        $config = config('webhook');

        return (new static())
            ->numberOfTry($config['max_try'])
            ->setTimeout($config['timeout'])
            ->setSsl($config['ssl'])
            ->setConcurrency($config['concurrency'])
            ->responseType($config['response_format']);
    }

    public function numberOfTry(int $count) {
        $this->webhook->numberOfTry = $count;
        return $this;
    }

    public function setTimeout(int $seconds) {
        $this->webhook->timeout = $seconds;
        return $this;
    }

    public function setSsl(bool $status) {
        $this->webhook->ssl = $status;
        return $this;
    }

    public function setConcurrency(int $count) {
        $this->webhook->concurrency = $count;
        return $this;
    }

    public function responseType(string $type = 'JSON') {
        $this->webhook->responseType = $type;
        return $this;
    }


    public function addVerb(string $method = 'POST') {
        $this->webhook->verb = $method;
        return $this;
    }

    public function addEndpoints(int $id) {
        $webhook = Webhook::find($id);
        if (isset($webhook)) {
            $this->webhook->endpoints[] = $webhook;
        }
        return $this;
    }

    /**
     * Lazy load webhooks collection form database
     * @return WebhookCall
     */
    public function storedEndpoints() {

        $endpoints = Webhook::cursor()->filter(function ($endpoint) {
            return $endpoint;
        });
        $this->fillEndPoints($endpoints);
        return $this;
    }

    public function clientEndpoints(User $user) {

        $endpoints = Webhook::cursor()->where('user_id', $user->id)
            ->filter(function ($endpoint) {
                return $endpoint;
            });
        $this->fillEndPoints($endpoints);
        return $this;
    }


    public function addPayload($payload) {
        $this->webhook->payload = $payload;
        return $this;
    }

    public function extraHeader(array $headers) {
        $this->webhook->headers = $headers;
        return $this;
    }

    public function dispatch(): void {
        //$this->tokenExist();
        //$this->urlExist();
        $this->hasEndpoint();
        //target handle default method in WebhookRepository
        dispatch($this->webhook);
    }

    /**
     * token filed to fire an endpoint
     * @return void
     * @throws Exception
     */
    private function tokenExist() {
        if (!$this->webhook->token) {
            // throw an exception
            $this->missingEx('token');
        }
    }

    /**
     * url filed to fire an endpoint
     * @return void
     * @throws Exception
     */
    private function urlExist() {
        if (!$this->webhook->url) {
            // throw an exception
            $this->missingEx('url');
        }
    }

    /**
     * desc
     * @return void
     * @throws Exception
     */
    private function hasEndpoint() {
        if (empty($this->webhook->endpoints)) {
            // throw an exception
            $this->missingEx('endpoints');
        }
    }

    /**
     * @param $endpoints
     */
    private function fillEndPoints($endpoints): void {
        foreach ($endpoints as $endpoint) {
            $endpoint->verb = $endpoint->verb ?? $this->webhook->verb;
            $this->webhook->endpoints [] = $endpoint;
        }
    }

    /**
     * desc
     * @param string $item // missing item in request
     * @return void
     * @throws Exception
     */
    private function missingEx(string $item) {
        throw  new Exception("Missing $item parameter");
    }
}
