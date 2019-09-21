<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase {

    use DatabaseMigrations;
    private $oldExceptionHandler;

    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication() {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    public function setUp(): void {
        parent::setUp();
        $this->artisan('db:seed');
    }

    protected function withoutExceptionHandling() {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {
            }

            public function report(\Exception $e) {
            }

            public function render($request, \Exception $e) {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling() {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
        return $this;
    }

    protected function sessionContent($response) {
        return $response->response->getOriginalContent() ?? null;
    }

    protected function getContent($response) {
        $res = (json_decode($response->response->getContent()));
        return $res;
    }

    protected function randomIndex($arrOrObj, int $from = 0) {
        return rand($from, sizeof($arrOrObj) - 1);
    }
}
