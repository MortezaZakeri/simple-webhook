<?php
/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Traits;


use Illuminate\Http\JsonResponse;

trait ResponseHandler {

    /**
     * Success response in REST api
     * @param null $data
     * @param string $message
     * @param int $code default 200 http OK
     * @param array $extra
     * @return JsonResponse
     */
    public function success($data = null, string $message = "", $extra = [], int $code = 200) {

        return $this->response([
            'data' => $data,
            'message' => $message,
            'extra' => $extra,
        ], $code);
    }

    /**
     * Error response has no data object
     * @param string $message
     * @param array $extra , can have extra info about error or exception
     * @param int default 400 http error
     * @return JsonResponse
     */
    public function error(string $message, array $extra = [], int $code = 400) {
        return $this->response([
            'message' => $message,
            'extra' => $extra
        ], $code);
    }

    public function unauthorized(string $message = 'Unauthorized') {
        return $this->response(['message' => $message], 401);
    }

    public function insufficient(string $message = 'Insufficient credit', int $code = 403) {
        return $this->response(['message' => $message], $code);
    }
    public function wrongParameters(string $message = 'Wrong parameters in query', int $code = 422) {
        return $this->response(['message' => $message], $code);
    }

    private function response(array $payload, $code) {
        return response()->json($payload, $code);
    }
}
