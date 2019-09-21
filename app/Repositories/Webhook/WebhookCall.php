<?php
/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Repositories\Webhook;

use App\Repositories\AppRepository;

class WebhookCall extends AppRepository {


    private $webhook;

    public function __construct() {
        $this->webhook = new WebhookEvent();
    }


    public static function make(): self {

        $config = config('webhook');

        return (new static())
            ->numberOfTry($config['max_try'])
            ->setSsl($config['ssl'])
            ->responseType($config['response_format']);
    }

    public function numberOfTry(int $count) {
        $this->webhook->numberOfTry = $count;
        return $this;
    }

    public function setSsl(bool $status) {
        $this->webhook->ssl = $status;
        return $this;
    }

    public function responseType(string $type = 'JSON') {
        $this->webhook->responseType = $type;
        return $this;
    }

    public function addUrl(string $url) {
        $this->webhook->url = $url;
        return $this;
    }

    public function addVerb(string $method = 'POST') {
        $this->webhook->method = $method;
        return $this;
    }

    public function addToken(string $token) {
        $this->webhook->token = $token;
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
        $this->tokenExist();
        $this->urlExist();
        //target handle default method in WebhookRepository
        dispatch($this->webhook);
    }

    /**
     * token filed to fire an endpoint
     * @return void
     */
    private function tokenExist() {
        if (!$this->webhook->token) {
            // throw an exception
        }
    }

    /**
     * url filed to fire an endpoint
     * @return void
     */
    private function urlExist() {
        if (!$this->webhook->url) {
            // throw an exception
        }
    }
}
