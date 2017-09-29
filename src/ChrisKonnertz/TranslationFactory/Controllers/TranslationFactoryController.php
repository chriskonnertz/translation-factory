<?php

namespace ChrisKonnertz\TranslationFactory\Controllers;

use Illuminate\Routing\Controller as BaseController;

class TranslationFactoryController extends BaseController
{

    /**
     * @param \Illuminate\Config\Repository $config
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(\Illuminate\Config\Repository $config)
    {
        // TODO Decide if this is a good idea
        if ($config->get('translation-factory.user_authentication') === null) {
            throw new \Exception(
                'Please publish the assets of the Translation Factory package via: '.
                '"php artisan vendor:publish '.
                '--provider="ChrisKonnertz\TranslationFactory\Integration\TranslationFactoryServiceProvider"'
            );
        }

        return view('translationFactory::page_base');
    }

}