<?php

namespace ChrisKonnertz\TranslationFactory\Controllers;

use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Config\Repository;
use Illuminate\Routing\Controller as BaseController;

class TranslationFactoryController extends BaseController
{

    /**
     * @var Repository
     */
    protected $config;

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

        $this->config = $config;
    }

    /**
     * Index page of the package
     *
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

        if (! $loggedIn) {
            #return redirect(url('/'));
        }

        $reader = $translationFactory->getTranslationReader();
        $translationBags = $reader->readAll();

        $baseLanguage = $this->config->get('app.locale');
        $targetLanguages = $translationFactory->getTargetLanguages();

        return view('translationFactory::home', compact('translationBags', 'baseLanguage', 'targetLanguages'));
    }

    /**
     * Logs the current user out and redirects to website
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        $translationFactory->getUserManager()->logoutCurrentUser();

        return redirect(url('/'));
    }

}