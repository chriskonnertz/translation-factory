<?php

namespace ChrisKonnertz\TranslationFactory\Controllers;

use Illuminate\Routing\Controller as BaseController;

class TranslationFactoryController extends BaseController
{
    public function index()
    {
        return view('translationFactory::page_base');
    }
}