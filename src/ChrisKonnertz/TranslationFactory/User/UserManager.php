<?php

namespace ChrisKonnertz\TranslationFactory\User;

use App\User;
use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Schema;

/**
 * The UserManager class is an abstraction of the User facade.
 * It can be replaced by a custom user manager in the config file.
 *
 * Attention: The user manager does not care if user authentication
 * has been activated in the config file or not.
 */
class UserManager implements UserManagerInterface
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * UserManager constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

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
     * Returns true if the current client is a user with admin permissions.
     * Returns false if the client is not logged in.
     *
     * @return bool
     */
    public function isAdmin()
    {
        if (! $this->isLoggedIn()) {
            return false;
        }

        $adminIds = $this->config->get(TranslationFactory::CONFIG_NAME.'.user_admin_ids');
        $currentUserId = $this->getCurrentUserId();

        $isAdmin = in_array($currentUserId, $adminIds);

        return $isAdmin;
    }

    /**
     * Returns the current user object or null.
     * Call getCurrentUserId() instead if you only
     * want to to get the ID of the current user.
     *
     * @return \Illuminate\Foundation\Auth\User|null
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
     * Logs the current user out
     *
     * @return void
     */
    public function logoutCurrentUser()
    {
        Auth::logout();
    }

    /**
     * This method throws an adequate exception if the user is not authenticated
     * but tries to access something that needs the user to be authenticated.
     *
     * @throws \Exception
     */
    public function throwAuthenticationException()
    {
        throw new AuthenticationException();
    }

    /**
     * Returns a collection of all users.
     *
     * @return Collection
     */
    public function findAllUsers()
    {
        return User::all();
    }

    /**
     * Returns a user who is identified by the ID
     *
     * @param int $id
     * @return \Illuminate\Foundation\Auth\User
     */
    public function findUser(int $id)
    {
        return User::whereId($id)->firstOrFail();
    }

    /**
     * Prepares the database (creates tables or add columns)
     */
    public function prepareDatabase()
    {
        // TODO Do not use the Schema facade but the underlying object instead.
        // Unknown how to get it though. \Illuminate\Database\Schema\Builder won't work and
        // \Illuminate\Database\Schema\MySqlBuilder is to specific.
        if (! Schema::hasColumn('users', TranslationFactory::DB_PREFIX.'_activated')) {
            // Add a column to the users table that decides if a user is activated
            Schema::table('users', function ($table) {
                $table->boolean(TranslationFactory::DB_PREFIX.'_activated')->default(false);
            });
        }
    }

}