<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Visit me on GitHub: https://github.com/chriskonnertz/translation-factory -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="ChrisKonnertz">
    <meta name="base-url" content="{!! url('/') !!}">
    <meta name="asset-url" content="{!! asset('') !!}">
    <meta name="csrf-token" content="{!! Session::get('_token') !!}">
    <meta name="locale" content="{!! Config::get('app.locale') !!}">

    <title>Laravel Translation Factory</title>

    <link rel="icon" type="image/png" href="{!! asset('img/favicon_180.png') !!}"><!-- Opera Speed Dial Icon -->
    <link rel="shortcut icon" type="picture/x-icon" href="{!! asset('favicon.png') !!}">

    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-exp.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-icons.min.css">

    <style>

    </style>
</head>
<body>
    <div class="container">
        <aside id="sidebar">
            <div class="logo">
                TranslationFactory
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="#">Home</a>
                    </li>
                    <li>
                        <a href="#">Website</a>
                    </li>
                    <li>
                        <a href="#">Support</a>
                    </li>
                    <li>
                        <a href="#">Logout</a>
                    </li>
                </ul>
            </nav>
        </aside>
        <section id="content">
            content
        </section>
    </div>

    <footer id="footer">
        <div>
            Created by <a href="#">Chris Konnertz</a> 2017. Licensed under the MIT License. More:
            <a href="#">GitHub</a>
        </div>
    </footer>
    </footer>
</body>
</html>