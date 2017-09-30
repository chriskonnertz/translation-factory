<?php

namespace ChrisKonnertz\TranslationFactory;

use ChrisKonnertz\TranslationFactory\User\UserManagerInterface;
use Illuminate\Config\Repository;

class TranslationFactory
{

    /**
     * Name of the config file (without extension) and name of the config namespace
     */
    const CONFIG_NAME = 'translation_factory';

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * TranslationFactory constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
        $this->userManager = $this->createUserManager();
    }

    /**
     * Create a new user manager and return it
     *
     * @return UserManagerInterface
     */
    protected function createUserManager()
    {
        $className = $this->config->get(self::CONFIG_NAME.'.user_manager');

        $object = app()->make($className);

        return $object;
    }

    /**
     * Getter for the user manager object
     *
     * @return UserManagerInterface
     */
    public function getUserManager() : UserManagerInterface
    {
        return $this->userManager;
    }

    /**
     * Setter for the user manager object
     *
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

}