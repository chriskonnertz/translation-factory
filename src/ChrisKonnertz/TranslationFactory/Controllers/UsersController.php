<?php

namespace ChrisKonnertz\TranslationFactory\Controllers;

use ChrisKonnertz\TranslationFactory\IO\TranslationReaderInterface;
use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Log\Writer as Log;

class UsersController extends AuthController
{

    /**
     * Index page of the users section
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->ensurePermission();

        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        $users = $translationFactory->getUserManager()->getAllUsers();

        return view('translationFactory::users', compact('users'));
    }

}
