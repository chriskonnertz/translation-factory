<?php

namespace ChrisKonnertz\TranslationFactory\Controllers;

use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Config\Repository as Config;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * TranslationFactoryController constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Ensure that the user is authenticated (if demanded).
     * Throws an exception if the user is not authenticated.
     * Per default, Laravel will redirect to the login page
     * if the exception is a AuthenticationException.
     *
     * @return void
     * @throws \Exception
     */
    protected function ensureAuth()
    {
        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        if ($this->config->get(TranslationFactory::CONFIG_NAME.'.user_authentication')) {
            $userManager = $translationFactory->getUserManager();

            if (! $userManager->isLoggedIn()) {
                $translationFactory->getUserManager()->throwAuthenticationException();
            }

            if (! $userManager->getCurrentUser()->{TranslationFactory::DB_PREFIX.'_activated'}) {
                // Admins do not have to be activated
                if ($userManager->isAdmin()) {
                    return;
                }

                // Throwing an authentication exception is not 100% right
                // (it should be an authorization exception) but easy to implement
                $translationFactory->getUserManager()->throwAuthenticationException();
            }
        }
    }

    /**
     * Ensure that the user is authorized to access the content.
     * Also ensures that the user is authenticated (if demanded).
     * Throws an exception if the user is not authorized.
     * Per default, Laravel simply will redirect to the login page
     * if the exception is a AuthenticationException.
     * That's not a perfect reaction but easy to implement
     * and it is only the server-side check, so the user should
     * never see it.
     * Attention: This method does not check if the user is activated,
     * so admins will always considered to be activated!
     *
     * @return void
     * @throws \Exception
     */
    protected function ensurePermission()
    {
        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        if ($this->config->get(TranslationFactory::CONFIG_NAME.'.user_authentication')) {
            $userManager = $translationFactory->getUserManager();

            if (! $userManager->isAdmin()) {
                // Throwing an authentication exception is not 100% right
                // (it should be an authorization exception) but easy to implement
                $translationFactory->getUserManager()->throwAuthenticationException();
            }
        }
    }

}
