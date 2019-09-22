<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->registerPolicies();
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot() {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }

    private function registerPolicies() {
        ///////////////// WEBHOOK POLICIES //////////////////////////
        Gate::define('NEW_WEBHOOK_ALLOWED', 'App\Policies\WebhookPolicy@newWebhook');
    }
}
