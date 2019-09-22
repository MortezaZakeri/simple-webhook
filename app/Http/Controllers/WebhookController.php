<?php

namespace App\Http\Controllers;


use App\Repositories\Webhook\WebhookCall;
use App\Repositories\Webhook\WebhookRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;


class WebhookController extends AppController {


    /**
     * @var WebhookRepository
     */
    private $repository;

    public function __construct(WebhookRepository $repository) {
        //check policies

        $this->repository = $repository;
    }

    public function myWebhooks(int $id = null) {

        $webhooks = $this->repository->get($this->user(), $id);
        return $this->success($webhooks, 'Webhooks list', [
            'count' => sizeof($webhooks)
        ], 200);
    }

    /**
     * create a new webhook for client
     * @param Request $request
     * check policies not exceed
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request) {

        if (!Gate::allows('NEW_WEBHOOK_ALLOWED', 1)) {
            return $this->insufficient();
        }
        $this->validate($request, [
            'token' => 'required|string|min:5|max:2048',
            'url' => 'required|string|min:8|max:2048',
        ]);
        $created = $this->repository->create($this->user(), $request['url'], $request['token']);
        if (isset($created)) {
            return $this->success(true, "Webhook has been created", [
                'data' => $created
            ], 201);
        }
        return $this->error("Cannot register a webhook", [], 400);
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

    /**
     * Call a webhook by payload
     * @param int|null $id
     * @return void
     */
    public function call(?int $id): void {
        if ($id) {
            WebhookCall::make()
                ->addVerb('POST')
                ->addPayload(['data' => '123456ABC'])
                ->addEndpoints($id)
                ->dispatch();
        } else {
            WebhookCall::make()
                ->addVerb('POST')
                ->addPayload(['data' => '123456ABC'])
                ->storedEndpoints()
                ->dispatch();
        }
    }
}
