<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Visit me on GitHub: https://github.com/chriskonnertz/translation-factory -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="ChrisKonnertz">
    <meta name="base-url" content="{!! url('/') !!}">
    <meta name="asset-url" content="{!! asset('') !!}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="locale" content="{!! Config::get('app.locale') !!}">

    <title>Laravel Translation Factory - @yield('title')</title>

    <link rel="icon" type="image/png" href="{!! asset('img/favicon_180.png') !!}"><!-- Opera Speed Dial Icon -->
    <link rel="shortcut icon" type="picture/x-icon" href="{!! asset('favicon.png') !!}">

    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-exp.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-icons.min.css">

    <style>
        ::-moz-selection { background: #454d5d; color: white }
        ::selection { background: #454d5d; color: white }
        noscript {
            display: block;
            padding: 1rem 4rem;
        }
        a, a:focus, a:hover, a:active {
            color: #f4645f;
            text-decoration: none !important;
        }
        a:focus {
            box-shadow: 0 0 0 0.1rem rgba(244,100,95,.2);
        }
        a[href=""] {
            cursor: default;
        }
        textarea {
            resize: vertical;
            min-height: 2rem;
        }

        .divider {
            clear: both;
        }
        .label.badge::after {
            background-color: #f4645f;
        }
        .form-input:focus, .form-select:focus {
            border-color: #c5c5c5;
            box-shadow: 0 0 0 0.1rem rgb(231, 233, 237);
        }
        .text-primary {
            color: #f4645f;
        }
        .toast.toast-error {
            background: #f4645f;
        }
        .btn {
            border-color: #f4645f;
            color: #f4645f;
        }
        .btn:focus {
            box-shadow: 0 0 0 0.1rem rgba(244,100,95,.2);
        }
        .btn:focus, .btn:hover {
            border-color: #f4645f;
            background-color: rgba(244,100,95,.1);
        }
        .btn:active {
            background-color: #f4645f;
        }
        .progress {
            background: #f0f1f4 linear-gradient(to right, #E7E9ED 30%, #f0f1f4 30%) top left/150% 150% no-repeat !important;
            display: block;
        }

        @media(max-width: 840px) {
            .table-wrapper {
                max-width: 100%;
                overflow-x: scroll;
            }
        }
        .save-error a {
            color: white;
            text-decoration-line: underline !important;
        }

        #sidebar {
            position: fixed;
            width: 10rem;
            height: 100vh;
            padding: 0.75rem;
            background-color: #f8f9fa;
        }
        #logo {
            display: block;
            margin-bottom: 32px;
            padding: 8px 16px;
            color: white;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-align: center;
            font-weight: bold;
            background: #f4645f;
            background: -moz-linear-gradient(45deg, #f4645f 0%, #f28885 100%);
            background: -webkit-linear-gradient(45deg, #f4645f 0%,#f28885 100%);
            background: linear-gradient(45deg, #f4645f 0%,#f28885 100%);
        }
        #logo:hover {
            text-shadow: 0 0 1px white;
        }
        #sidebar .hamburger {
            display: none;
            text-align: right;
            cursor: pointer;
        }
        #sidebar .hamburger:focus {
            box-shadow: none;
        }
        #nav {
            transition: max-height 0.5s;
        }
        #nav li {
            display: block;
        }
        #nav a {
            display: block;
            padding: 0.25rem 0;
            color: #999;
            font-weight: bold;
        }
        #nav a:hover {
            color: #f4645f !important;
        }
        #main-container {
            margin-left: 10rem;
        }
        #content {
            min-height: 100vh;
            margin-bottom: 0;
            padding: 1rem 4rem 4rem 4rem; /* padding-bottom = 1rem + 3rem(=footer height) */
        }
        #footer {
            position: relative;
            margin-top: -3rem; /* = total height of the footer */
            padding: 1rem 4rem;
            line-height: 1rem;
            color: #999;
        }

        @media(max-width: 840px) {
            #sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }
            #sidebar .hamburger {
                display: block;
            }
            #nav {
                max-height: 0;
                overflow: hidden;
            }
            #nav.visible {
                max-height: 50rem; /* = total height of the nav */
            }
            #main-container {
                margin-left: 0;
            }
            #content {
                padding-right: 1rem;
                padding-left: 1rem;
            }
        }

        .bag-tile-wrapper {
            box-sizing: border-box;
            float: left;
            width: 33%;
            padding-right: 1rem;
        }
        .bag-tile {
            border: 1px solid #EEE;
            margin: 1rem 0;
            padding: 1rem;
        }
        .bag-tile:hover {
            border-color: #DDD;
        }
        .bag-tile .icon-wrapper {
            width: 2rem;
            height: 2rem;
            padding: 0.45rem 0;
            background-color: #f4645f;
            color: white;
            text-align: center;
        }
        .bag-tile .tile-title {
            font-weight: bold;
        }
        .bag-tile .tile-subtitle {
            margin: 0;
        }
        @media(max-width: 600px) {
            .bag-tile-wrapper {
                width: 100%;
            }
        }
        @media(min-width: 601px) and (max-width: 1280px) {
            .bag-tile-wrapper {
                width: 50%;
            }
        }

        .initial-info-text {
            margin-bottom: 2rem;
        }
        .items-box ul {
            margin: 1rem 0 2rem 0;
            max-height: 340px; /* 34px * 10 = 340px */
            overflow-y: auto;
            border: 1px solid #EEE;
        }
        .items-box li {
            display: block;
            margin: 0;
        }
        .items-box li:hover {
            background-color: #FBFBFB;
        }
        .items-box li.current {
            background-color: #f4645f;
        }
        .items-box .is-translated {
            font-weight: bold;
        }
        .items-box .is-not-translated {
            color: #DDD
        }
        .items-box li.current a, .items-box li.current .text-gray {
            color: white;
        }
        .items-box a {
            display: block;
            padding: 0.25rem 0.5rem;
        }
        .item-box blockquote {
            margin: 0;
        }
        .item-box blockquote span {
            font-weight: bold;
        }
        .item-box .button-bar {
            margin-top: 0.4rem;
        }
    </style>
</head>
<body>
    <aside id="sidebar">
        <a id="logo" class="rounded" href="{{ url('/translation-factory') }}">
            Translation<br>
            Factory
        </a>
        <a href="#" class="hamburger">
            <i class="icon icon-menu"></i>
        </a>
        <nav id="nav">
            <ul>
                <li>
                    <a href="{{ url('/translation-factory') }}">Start</a>
                </li>
                <li>
                    <a href="{{ url('/') }}">Frontend</a>
                </li>
                <li>
                    <a href="https://github.com/chriskonnertz/translation-factory/issues" target="_blank">Support</a>
                </li>
                <li>
                    <a href="{{ url('/translation-factory/config') }}">Config</a>
                </li>
                @auth
                    <li>
                        <a href="{{ url('/translation-factory/logout') }}">Logout</a>
                    </li>
                @endauth
            </ul>
        </nav>
    </aside>

    <div id="main-container">
        <noscript>
            <div class="toast toast-warning">JavaScript is not enabled. We highly recommend to enable it!</div>
        </noscript>

        <section id="content">
            @section('content')
                Hello, world!
            @show
        </section>

        <footer id="footer">
            Version {{ \ChrisKonnertz\TranslationFactory\TranslationFactory::VERSION }}.
            Created by <a href="http://www.chriskonnertz.de/" target="_blank">Chris Konnertz</a> in 2017.
            Licensed under the MIT License.
            More on <a href="https://github.com/chriskonnertz/translation-factory" target="_blank">GitHub</a>.
        </footer>
    </div>

    <script>
        (function () {
            var hamburger = document.querySelector('#sidebar .hamburger');
            var nav = document.getElementById('nav');

            hamburger.addEventListener('click', function(event)
            {
                nav.classList.toggle('visible');
            });
        })();
    </script>
</body>
</html>
