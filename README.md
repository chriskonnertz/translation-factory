# Laravel Translation Factory

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/chriskonnertz/translation-factory/master/LICENSE)

Translation Factory is a tool for the Laravel framework that helps to create and manage translations.
Especially it helps to coordinate multiple translators, aiding them with AI translations.

```
╔════════════════════════════════════════════╗
  ⚠ Work in progress - not ready for use! ⚠ 
╚════════════════════════════════════════════╝
```

> Note: "Factory" does not mean the pattern here but rather this: 🏭

## Highlights

* Seamless integration into your existing Laravel application
* Uses [DeepL](https://www.deepl.com/) - currently the best machine translation engine - to auto-translate texts
* Beautiful user inteface and made with a good user experience in mind
* Well prepared for mobile devices (smartphones and tablets)
* Exlusively made for Laravel which makes the installation a piece of cake
* Highly configurable and easy to extend
* Open source and free even for commercial use

## Installation

Through [Composer](https://getcomposer.org/):

```
composer require chriskonnertz/translation-factory
```

> This library requires PHP 7.0 or higher with the cURL extension and Laravel 5.5.

This library makes use of Laravel's 
[package auto-discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518)
 so it will be auto-detected.
 
Nevertheless you have to publish the assets via: `php artisan vendor:publish --provider="ChrisKonnertz\TranslationFactory\Integration\TranslationFactoryServiceProvider`

Then you may navigate to `http://<your-domain>/translation-factory` to start. 

## Prepare Laravel

This package supports user authentication. Per default it depends on Laravel's built-in user authentication system.
If you already use Laravel's user authentication then you can skip this section. 
But if you have a fresh installation of Laravel follow these steps to prepare it:

1. Via a console run `php artisan make:auth` to create resources like a controller and views
2. Then run `php artisan migrate` to prepare the database

Now the translators will be able to navigate to `http://<your-domain>/home` and log in or create a new user account.

> If you do not want to use Laravel's built-in user authentication system you have to create your own user manager 
that implements the `UserManagerInterface`. Introduce it to Translation Factory by adding its name to the config file
(key: `user_manager`).

## Use With External Translators

If you want to use Translation Factory to let externals translators translate your texts, this is the recommended way:

1. Setup a new server with your application. The server has to be reachable from the outside.
2. Make sure Translation Factory can write into the output directories.
3. Let the externals create their user accounts (`http://<your-domain>/register`)
4. Spread the link: `http://<your-domain>/translation-factory`

## Configuration

Open `config/translation_factory.php` to change the configuration. All settings are documented.

## Backups

The default behaviour of Translation Factory is to make daily backups of all translation files
 that it wants to overwrite. They will be stored in `<storage-path>/app/translations` which usually
 translates to `storage/app/translations`. You may change this path in the config file (key: `backup_dir`). 
 The names of the backup files will be built of a hash and the date and use ".backup" as extension.

## Current State

This is an MVP (minimum viable product). The code quality is okay, but for sure it is not great. 
There is a lot of space for refactoring. This will happen if it turns out that this package actually meets 
someones needs. 


## FAQ

* **Does this also work with Laravel 5.4?** Maybe. Not tested, though. And you have to register the `TranslationFactoryServiceProvider`.
* **Which languages can be auto-translated?** Here is a list: [Link](https://github.com/chriskonnertz/DeepLy#supported-languages)
* **Should I use [barryvdh/laravel-translation-manager](https://github.com/barryvdh/laravel-translation-manager)?**
LTM offers some features that help to handle translations, for example finding translations that are missing in the
translations files. That makes it a good addition to Translation Factory. Translation Factory on the other hand is
 focused on translating. It has a sophisticated user interface and uses DeepL to help the translator, which makes it a
 good choice for translating.
* **I got this exception: "SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes"**
Here is a solution: [Link](https://laravel-news.com/laravel-5-4-key-too-long-error)
