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

];