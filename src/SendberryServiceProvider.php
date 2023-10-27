<?php

namespace NotificationChannels\Sendberry;

use Illuminate\Support\ServiceProvider;

class SendberryServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(SendberryApi::class, function ($app) {
            $username = $this->app['config']['services.sendberry.username'];
            $password = $this->app['config']['services.sendberry.password'];
            $authKey = $this->app['config']['services.sendberry.auth_key'];
            $from = $this->app['config']['services.sendberry.from'];
            $testMode = $this->app['config']['services.sendberry.test_mode'];
            $webhook = $this->app['config']['services.sendberry.webhook'];
            $client = new SendberryApi($authKey, $username, $password, $from, $webhook, $testMode);

            return $client;
        });
    }

    public function provides(): array
    {
        return [
            SendberryApi::class,
        ];
    }
}
