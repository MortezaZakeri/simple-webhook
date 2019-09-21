<?php
/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Repositories\Webhook;

use App\Models\Webhook;
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

    public function addEndpoints(string $token, string $url) {
        $this->webhook->endpoints[] = [
            'token' => $token,
            'url' => $url
        ];
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
        foreach ($endpoints as $endpoint) {
            $endpoint->verb = $endpoint->verb ?? $this->webhook->verb;
            $this->webhook->endpoints [] = $endpoint;
        }
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
        $this->hasEndpoint();
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

    private function hasEndpoint() {
        if (empty($this->webhook->endpoints)) {
            // throw an exception
        }
    }
}
