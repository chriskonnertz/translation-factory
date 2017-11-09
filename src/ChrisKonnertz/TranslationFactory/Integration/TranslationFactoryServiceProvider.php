<?php

namespace ChrisKonnertz\TranslationFactory\Integration;

use ChrisKonnertz\TranslationFactory\Controllers\TranslationFactoryController;
use ChrisKonnertz\TranslationFactory\Controllers\TranslationFileController;
use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Support\ServiceProvider;

class TranslationFactoryServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap events.
     *
     * @return void
     */
    public function boot()
    {
        // Register the config file for being published
        $this->publishes([
            __DIR__ . '/../../../../config/config.php' => config_path('translation_factory.php')
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('translation-factory', function($app)
        {
            return new TranslationFactory($app['config'], $app['Illuminate\Contracts\Cache\Repository']);
        });

        $this->registerViews();

        $this->registerRoutes();
    }

    /**
     * Register the views directory
     *
     * @return void
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
        $router->post('translation-factory', $controllerName . '@update');
        $router->get('translation-factory/config', $controllerName . '@config');
        $router->get('translation-factory/logout', $controllerName . '@logout');

        $controllerName = TranslationFileController::class;
        $router->get('translation-factory/file/{hash}', $controllerName . '@index');
        $router->get('translation-factory/file/{hash}/item/{currentItemKey}', $controllerName . '@edit');
        $router->post('translation-factory/file/{hash}/item/{currentItemKey}', $controllerName . '@update');
    }

}
