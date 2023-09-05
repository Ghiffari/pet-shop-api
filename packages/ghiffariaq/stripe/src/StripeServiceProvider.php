<?php

namespace Ghiffariaq\Stripe;

use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'stripe');
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/stripe.php', 'stripe');

    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/stripe.php' => config_path('stripe.php'),
        ], 'stripe.config');
    }
}
