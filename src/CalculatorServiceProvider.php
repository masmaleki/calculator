<?php

namespace Masmaleki\Calculator;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class CalculatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        $this->loadViewsFrom(__DIR__.'/resources/views', 'calculator');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('calculator.php'),
            ], 'Calculator-Config');

            $this->publishes([
                __DIR__.'/resources/views' => resource_path('views/vendor/calculator'),
            ], 'Calculator-Views');

            $this->publishes([
                __DIR__.'/resources/assets' => public_path('vendor/calculator'),
            ], 'Calculator-Assets');

            $this->publishes([
                __DIR__.'/../tests' => public_path('../tests/Unit'),
            ], 'Calculator-Tests');

        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */

    public function register()
    {

        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'calculator');

        // Register the main class to use with the facade
        $this->app->singleton('calculator', function () {
            return new Calculator;
        });
        $this->registerRoutes($this->app);
    }

    /**
     * Register the factory class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    /**
     * Get the routes services provided by the provider.
     *
     * @return routes
     */
    protected function registerRoutes(Application $app)
    {
        $app['router']->group(['namespace' => 'Masmaleki\Calculator\App\Http\Controllers', "prefix" => "calculator",'middleware' => ['web']], function () {
            require __DIR__ . '/routes.php';
        });
    }
}
