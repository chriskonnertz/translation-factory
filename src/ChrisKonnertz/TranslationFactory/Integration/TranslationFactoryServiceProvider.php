<?php

namespace ChrisKonnertz\TranslationFactory\Integration;

use ChrisKonnertz\TranslationFactory\Controllers\TranslationFactoryController;
use Illuminate\Support\ServiceProvider;

class TranslationFactoryServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../../../../config/config.php' => config_path('translation-factory.php')]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRoutes();

        $view = $this->app->get('view');

        // Register views directory
        \View::addNamespace('translationFactory', realpath(__DIR__.'/../resources/views'));
    }

    /**
     * Register the routes
     *
     * @return void
     */
    protected function registerRoutes()
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app->get('router');

        $controllerName = TranslationFactoryController::class;
        $router->get('translation-factory', $controllerName.'@index');
    }

}