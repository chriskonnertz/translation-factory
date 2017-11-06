# Laravel Translation Factory

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/chriskonnertz/translation-factory/master/LICENSE)

Translation Factory is a tool for the Laravel framework that helps to create and manage translations.
Especially it helps to coordinate multiple translators, aiding them with AI translations.

```
╔════════════════════════════════════════════╗
║ ⚠ Work in progress - not ready for use! ⚠ ║
╚════════════════════════════════════════════╝
```

> Note: "Factory" does not mean the pattern here but rather this: 🏭

## Installation

Through [Composer](https://getcomposer.org/):

```
composer require chriskonnertz/translation-factory
```

> This library requires PHP 7.0 or higher with the cURL extension and Laravel 5.5.

This library makes use of Laravel's 
[package auto-discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518)
 so it will be auto-detected.
 
Now please publish the assets of the Translation Factory package via: `php artisan vendor:publish --provider="ChrisKonnertz\TranslationFactory\Integration\TranslationFactoryServiceProvider` (or by manually copying the config file to the `app/config` folder)
 
After setup is complete, navigate to `http://<your-domain>/translation-factory` to start.

## Prepare Laravel

This package supports user authentication. Per default it depends on Laravel's built-in user authentication system.
If you already use Laravel's user authentication then you can skip this section. 
But if you have a fresh installation of Laravel follow these steps to prepare it:

1. Via a console run `php artisan make:auth` to create resources like a controller and views
2. Then run `php artisan migrate` to prepare the database

Now the translators will be able to navigate to `http://<your-domain>/home` and log in or create a new user account.

> If you do not want to use Laravel's built-in user authentication system you have to create your own user manager 
that implements the `UserManagerInterface`. Introduce it to Translation Factory by adding its name to the config file
(key: `user_manager`). Publish the config with `php artisan vendor:publish`.

## Use with External Translators

If you want to use Translatio Factory to let externals translators translate your texts, this is the recommended way:

1. Setup a new server with your application. The server has to be reachable from the outside.
2. Make sure Translation Factory can write into the output directories.
3. Let the externs create their user accounts (`http://<your-domain>/home`)
4. Happy translating!
