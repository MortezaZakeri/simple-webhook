<?php
/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Traits;


trait ResponseHandler {

    /**
     * Success response in REST api
     * @param null $data
     * @param string $message
     * @param int $code default 200 http OK
     * @param array $extra
     * @return void
     */
    public function success($data = null, string $message = "", int $code = 200, $extra = []) {

        return $this->response([
            'data' => $data,
            'message' => $message,
            'extra' => $extra,
        ], $code);
    }

    /**
     * Error response has no data object
     * @param string $message
     * @param int default 400 http error
     * @param array $extra , can have extra info about error or exception
     * @return bool
     */
    public function error(string $message, int $code = 400, $extra = []) {
        return $this->response([
            'message' => $message,
            'extra' => $extra
        ], $code);
    }

    private function response(array $payload, $code) {
        return response()->json($payload, $code);
    }
}
