<?php

namespace ChrisKonnertz\TranslationFactory;

use ChrisKonnertz\TranslationFactory\IO\TranslationReaderInterface;
use ChrisKonnertz\TranslationFactory\IO\TranslationWriterInterface;
use ChrisKonnertz\TranslationFactory\User\UserManagerInterface;
use Illuminate\Config\Repository;

class TranslationFactory
{

    /**
     * The version number
     */
    const VERSION = '0.0.1-dev';

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
     * @var TranslationReaderInterface
     */
    protected $translationReader;

    /**
     * @var TranslationWriterInterface
     */
    protected $translationWriter;

    /**
     * TranslationFactory constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
        $this->userManager = $this->createUserManager();
        $this->translationReader = $this->createTranslationReader();
        $this->translationWriter = $this->createTranslationWriter();
    }

    /**
     * Create a new user manager and return it
     *
     * @return UserManagerInterface
     */
    protected function createUserManager() : UserManagerInterface
    {
        $className = $this->config->get(self::CONFIG_NAME.'.user_manager');

        $object = app()->make($className);

        return $object;
    }

    /**
     * Create a new translation reader and return it
     *
     * @return TranslationReaderInterface
     */
    protected function createTranslationReader() : TranslationReaderInterface
    {
        $className = $this->config->get(self::CONFIG_NAME.'.translation_reader');

        $object = app()->make($className);

        return $object;
    }

    /**
     * Create a new translation writer and return it
     *
     * @return TranslationWriterInterface
     */
    protected function createTranslationWriter() : TranslationWriterInterface
    {
        $className = $this->config->get(self::CONFIG_NAME.'.translation_writer');

        $object = app()->make($className);

        return $object;
    }

    /**
     * Getter for the user manager
     *
     * @return UserManagerInterface
     */
    public function getUserManager() : UserManagerInterface
    {
        return $this->userManager;
    }

    /**
     * Setter for the user manager
     *
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * Getter for the translation reader
     *
     * @return TranslationReaderInterface
     */
    public function getTranslationReader() : TranslationReaderInterface
    {
        return $this->translationReader;
    }

    /**
     * Setter for the translation reader
     *
     * @param TranslationReaderInterface $translationReader
     */
    public function setTranslationReader(TranslationReaderInterface $translationReader)
    {
        $this->translationReader = $translationReader;
    }

    /**
     * Getter for the translation writer
     *
     * @return TranslationWriterInterface
     */
    public function getTranslationWriter() : TranslationWriterInterface
    {
        return $this->translationWriter;
    }

    /**
     * Setter for the translation writer
     *
     * @param TranslationWriterInterface $translationWriter
     */
    public function setTranslationWriter(TranslationWriterInterface $translationWriter)
    {
        $this->translationWriter = $translationWriter;
    }

}