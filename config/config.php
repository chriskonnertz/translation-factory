<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable User Authentication
    |--------------------------------------------------------------------------
    |
    | Set this to true to enable user authentication, false otherwise
    |
    */
    'user_authentication' => true,

    /*
    |--------------------------------------------------------------------------
    | User Manager Class
    |--------------------------------------------------------------------------
    |
    | This is the full qualified name of the class that is used to build
    | the user manager object. If you want to use something else than
    | Laravel's default user system you have to write your own user
    | manager class and replace the default class with your class
    |
    */
    'user_manager' => \ChrisKonnertz\TranslationFactory\User\UserManager::class,

    /*
    |--------------------------------------------------------------------------
    | Translation Reader Class
    |--------------------------------------------------------------------------
    |
    | This is the full qualified name of the class that is used to build
    | the translation reader object. If you want to use something else
    | than Laravel's default translation system you have to write
    | your own user reader class and replace the current value
    |
    */
    'translation_reader' => \ChrisKonnertz\TranslationFactory\IO\TranslationReader::class,

    /*
    |--------------------------------------------------------------------------
    | Translation Writer Class
    |--------------------------------------------------------------------------
    |
    | This is the full qualified name of the class that is used to build
    | the translation writer object. If you want to use something else
    | than Laravel's default translation system you have to write
    | your own user writer class and replace the current value
    |
    */
    'translation_writer' => \ChrisKonnertz\TranslationFactory\IO\TranslationWriter::class,

];