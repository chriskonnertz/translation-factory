<?php

namespace ChrisKonnertz\TranslationFactory\User;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

/**
 * The UserManager class is an abstraction of the User facade.
 * It can be replaced by a custom user manager in the config file.
 */
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

    /**
     * Logs out the current user
     *
     * @return void
     */
    public function logoutCurrentUser()
    {
        Auth::logout();
    }

    /**
     * This method throws an adequate exception if the user is not authenticated
     * but tries to access something that needs the user to be authorized.
     *
     * @throws \Exception
     */
    public function throwAuthenticationException()
    {
        throw new AuthenticationException();
    }

}