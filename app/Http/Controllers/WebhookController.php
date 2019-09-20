<?php

namespace App\Http\Controllers;


use App\Repositories\Webhook\WebhookCallRepository;
use Illuminate\Http\Request;

class WebhookController extends AppController {


    public function __construct() {
        //check policies
    }

    public function myWebhooks(int $id = null) {

    }

    /**
     * create a new webhook for client
     * @param Request $request
     * @return void heck policies
     * check policies not exceed
     */
    public function create(Request $request) {
        WebhookCallRepository::make()
            ->addUrl('')
            ->addToken('')
            ->addVerb()
            ->addPayload();
    }

    /**
     * Update specific webhook for client
     * @param Request $request (only url and token)
     * @return void
     * check policies  belongs to me
     */
    public function update(Request $request) {

    }

    /**
     * Delete specific webhook for client
     * @param Request $request
     * @return void
     * check policies  belongs to me
     */
    public function delete(Request $request) {

    }
}
