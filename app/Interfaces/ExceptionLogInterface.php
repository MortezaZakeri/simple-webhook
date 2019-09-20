<?php
/**
 * User: MB
 * Date: 9/20/2019
 */

namespace App\Interfaces;


interface ExceptionLogInterface {

    /**
     * log exceptions handled in try catch blocks, APP level exceptions
     * @param string $method
     * @param $exception
     * @param string $message (developer custom message)
     * @param int $risk (define by developer from 1-5 low to high)
     * @return bool
     */
    public function log(string $method, $exception, string $message, int $risk): bool;
}
