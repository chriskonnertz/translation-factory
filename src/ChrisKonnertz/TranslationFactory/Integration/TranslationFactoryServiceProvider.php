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
        // Register the config file for being published
        $this->publishes([
            __DIR__ . '/../../../../config/config.php' => config_path('translation-factory.php')
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerViews();

        $this->registerRoutes();
    }

    /**
     * Register the views directory
     *
     * @erturn void
     */
    protected function registerViews()
    {
        /** @var \Illuminate\View\Factory $view */
        $view = $this->app->get('view');

        $view->addNamespace('translationFactory', realpath(__DIR__.'/../resources/views'));
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
        $router->get('translation-factory', $controllerName . '@index');
    }

}