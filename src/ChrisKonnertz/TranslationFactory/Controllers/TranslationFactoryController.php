<?php

namespace ChrisKonnertz\TranslationFactory\Controllers;

use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Config\Repository;
use Illuminate\Routing\Controller as BaseController;

class TranslationFactoryController extends BaseController
{

    /**
     * TranslationFactoryController constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        if ($config->get(TranslationFactory::CONFIG_NAME.'.user_authentication')) {
            //$this->middleware('auth');
        }
    }

    /**
     * @param Repository $config
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Repository $config)
    {
        // TODO Decide if this is a good idea
        if ($config->get(TranslationFactory::CONFIG_NAME.'.user_authentication') === null) {
            throw new \Exception(
                'Please publish the assets of the Translation Factory package via: '.
                '"php artisan vendor:publish '.
                '--provider="ChrisKonnertz\TranslationFactory\Integration\TranslationFactoryServiceProvider"'
            );
        }

        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        $loggedIn = $translationFactory->getUserManager()->isLoggedIn();

        return view('translationFactory::page_base');
    }

}