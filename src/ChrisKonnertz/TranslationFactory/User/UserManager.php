<?php

namespace ChrisKonnertz\TranslationFactory\User;

use Illuminate\Support\Facades\Auth;

class UserManager implements UserManagerInterface
{

    /**
     * Returns true if the current client is an authenticated user.
     * Call getCurrentUser() to retrieve the user object.
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return Auth::check();
    }

    /**
     * Returns the current user object or null.
     * Call getCurrentUserId() instead if you only
     * want to to get the ID of the current user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getCurrentUser()
    {
        return Auth::user();
    }

    /**
     * Returns the ID of the current user or null
     *
     * @return int|null
     */
    public function getCurrentUserId()
    {
        return Auth::id();
    }

}