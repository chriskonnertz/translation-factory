<?php

namespace ChrisKonnertz\TranslationFactory\User;

use Illuminate\Database\Eloquent\Collection;

/**
 * A class that implements this interface is an abstraction of the User facade.
 * It can be replaced by a custom user manager in the config file.
 *
 * Attention: The user manager must not care if user authentication
 * has been activated in the config file or not.
 */
interface UserManagerInterface
{

    /**
     * Returns true if the current client is an authenticated user.
     * Use getCurrentUser() to retrieve the user object.
     *
     * @return bool
     */
    public function isLoggedIn();

    /**
     * Returns true if the current client is a user with admin permissions.
     * Returns false if the client is not logged in.
     *
     * @return bool
     */
    public function isAdmin();

    /**
     * Returns the current user object or null.
     * The user object should be or extend the \App\User class.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getCurrentUser();

    /**
     * Returns the ID of the current user or null
     *
     * @return int|null
     */
    public function getCurrentUserId();

    /**
     * Logs the current user out
     *
     * @return void
     */
    public function logoutCurrentUser();

    /**
     * This method throws an adequate exception if the user is not authenticated
     * but tries to access something that needs the user to be authenticated.
     *
     * @throws \Exception
     */
    public function throwAuthenticationException();

    /**
     * Returns a collection of all users.
     * The user objects should be or extend the \App\User class.
     *
     * @return Collection
     */
    public function getAllUsers();

}