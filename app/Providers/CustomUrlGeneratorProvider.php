<?php

namespace App\Providers;

use App\Custom\CustomUrlGenerator;
use Illuminate\Support\ServiceProvider;

class CustomUrlGeneratorProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("url", function ($app) {
            $routes = $app['router']->getRoutes();
            $app->instance('routes', $routes);

            return new CustomUrlGenerator(
                $routes,
                $app->rebinding(
                    'request',
                    static function ($app, $request) {
                        $app['url']->setRequest($request);
                    }
                ),
                $app['config']['app.asset_url']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
