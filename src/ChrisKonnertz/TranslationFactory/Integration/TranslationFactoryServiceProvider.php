<?php

namespace ChrisKonnertz\TranslationFactory\Integration;

use Illuminate\Support\ServiceProvider;

class TranslationFactoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerRoutes();

        // Register views directory
        \View::addNamespace('translationFactory', realpath(__DIR__.'/../resources/views'));
    }

    protected function registerRoutes()
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app->get('router');

        $controllerName = \ChrisKonnertz\TranslationFactory\Controllers\TranslationFactoryController::class;
        $router->get('translation-factory', $controllerName.'@index');
    }

}
