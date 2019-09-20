<?php
/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Traits;


use App\Interfaces\ExceptionLogInterface;

trait ExceptionHandler {

    public function logException(ExceptionLogInterface $exceptionLog,
                                 string $method, $exception, string $message, int $risk) {
        return $exceptionLog->log($method, $exception, $message, $risk);
    }
}
