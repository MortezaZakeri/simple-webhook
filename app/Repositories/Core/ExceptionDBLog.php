<?php
/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Repositories\Core;


use App\Interfaces\ExceptionLogInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Exception as ExceptionModel;

class ExceptionDBLog implements ExceptionLogInterface {

    /**
     * Log exception in database table exceptions
     * @param string $method
     * @param $exception , standard laravel exception
     * @param string $message
     * @param int $risk
     * @return bool
     */
    public function log(string $method, $exception, string $message = '', int $risk = 1): bool {

        try {
            ExceptionModel::create([
                'method' => $method,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'developer_message' => $message,
                'risk' => $risk,
            ]);
            return true;
        } catch (Exception $ex) {
            // exception inside exception handler
            Log::log('DB', $ex->getMessage());
            return false;
        }
    }
}
