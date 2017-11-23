<?php

namespace ChrisKonnertz\TranslationFactory\Controllers;

use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{

    /**
     * Ensure that the user is authorized to access the content.
     * Throws an exception if the suer is not authorized.
     * Per default, laravel will redirect to the login apge
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
            $loggedIn = $translationFactory->getUserManager()->isLoggedIn();

            if (! $loggedIn) {
                $translationFactory->getUserManager()->throwAuthenticationException();
            }
        }
    }

}
