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

    public function myWebhooks(Request $request,int $id = null) {

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
            'url' => 'required|url|min:8|max:2048',
        ]);
        $created = $this->repository
            ->create($this->user(), $request['url'], $request['token'], $request['verb'] ?? null);
        if (isset($created)) {
            return $this->success(true, "Webhook has been created / updated", [
                'data' => $created
            ], 201);
        }
        return $this->error("Cannot register a webhook", [], 400);
    }


    public function triggerAll(Request $request) {
        //        /"payload": [ "any" , { "valid": "JSON" }]

        $attributes = $this->validate($request, [
            'payload' => 'required|array',
            'payload.*' => 'required',
        ]);
        if (in_array('any', $payload = $attributes['payload']) && is_array($payload[1])) {

            if ($payload[1]['valid'] == 'JSON' || $payload[1]['valid'] == 'json') {
                WebhookCall::make()
                    ->addVerb('POST')
                    ->addPayload(['data' => '123456ABC'])
                    ->clientEndpoints($this->user())
                    ->dispatch();
                return $this->success(true, 'Done', [], 200);
            }
        }
        return $this->wrongParameters();

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
