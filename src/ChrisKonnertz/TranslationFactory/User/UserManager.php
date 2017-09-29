<?php

namespace ChrisKonnertz\TranslationFactory\User;

use Illuminate\Support\Facades\Auth;

class UserManager implements UserManagerInterface
{

    /**
     * Returns true if the current client is an authenticated user.
     * Use getCurrentUser() to retrieve the user object.
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return (Auth::user() !== null);
    }

    /**
     * Returns the current user object or null
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