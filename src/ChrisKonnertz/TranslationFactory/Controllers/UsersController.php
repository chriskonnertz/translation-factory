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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->ensurePermission();

        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        $users = $translationFactory->getUserManager()->findAllUsers();

        $dbPrefix = TranslationFactory::DB_PREFIX;

        $adminIds = $this->config->get(TranslationFactory::CONFIG_NAME.'.user_admin_ids');

        return view('translationFactory::users', compact('users', 'adminIds', 'dbPrefix'));
    }

    /**
     * Activate or deactivate a user account (except admin accounts)
     *
     * @param Request $request
     * @param int     $id
     * @return \Illuminate\View\View
     */
    public function toggleActivation(Request $request, int $id)
    {
        $this->ensurePermission();

        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        // Prevent changing the admin activation state
        $adminIds = $this->config->get(TranslationFactory::CONFIG_NAME.'.user_admin_ids');
        if (in_array($id, $adminIds)) {
            $request->session()->flash('message', 'Cannot toggle activation of an administrator!');
            return $this->index();
        }

        $user = $translationFactory->getUserManager()->findUser($id);
        $active = $user->{TranslationFactory::DB_PREFIX.'_activated'};

        // Toggle (=invert) state. It is a boolean value.
        $active = ! $active;

        $user->{TranslationFactory::DB_PREFIX.'_activated'} = $active;
        $user->save();

        $request->session()->flash('message', 'Toggled activation of the user "'.$user->name.'".');
        return redirect('/translation-factory/users');
    }

}
