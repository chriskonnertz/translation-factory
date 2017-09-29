<?php

namespace ChrisKonnertz\TranslationFactory\User;

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
     * Returns the current user object or null.
     * Call getCurrentUserId() instead if you only
     * want to to get the ID of the current user.
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
     * Logs out the current user
     *
     * @return void
     */
    public function logoutCurrentUser();

}