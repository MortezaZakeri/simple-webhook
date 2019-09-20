<?php

use App\Repositories\Core\ExceptionDBLog;
use App\Traits\ExceptionHandler;

/**
 * User: MB
 * Date: 9/20/2019
 */
class AppRepository {

    use ExceptionHandler;

    public function handleException(string $method, $exception, string $message, int $risk): bool {

        return $this->logException(new ExceptionDBLog(), $method, $exception, $message, $risk);
    }
}
