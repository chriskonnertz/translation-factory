<?php

namespace ChrisKonnertz\TranslationFactory\Controllers;

use ChrisKonnertz\TranslationFactory\IO\TranslationReaderInterface;
use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Config\Repository;
use Illuminate\Routing\Controller as BaseController;

class TranslationFileController extends BaseController
{

    /**
     * @var Repository
     */
    protected $config;

    /**
     * TranslationFactoryController constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        if ($config->get(TranslationFactory::CONFIG_NAME.'.user_authentication')) {
            //$this->middleware('auth');
        }

        $this->config = $config;
    }

    /**
     * Index page of the package
     *
     * @param string $hash $config
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(string $hash)
    {
        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        $loggedIn = $translationFactory->getUserManager()->isLoggedIn();

        if (! $loggedIn) {
            #return redirect(url('/'));
        }

        $translationReader = $translationFactory->getTranslationReader();
        $translationBag = $this->getBagByHash($translationReader, $hash);

        $currentItemKey = null;
        return view('translationFactory::file', compact('translationBag', 'currentItemKey'));
    }

    /**
     * Shows the translation file page with a text area for editing a translation item
     *
     * @param string $hash
     * @param string $currentItemKey
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $hash, string $currentItemKey)
    {
        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        $loggedIn = $translationFactory->getUserManager()->isLoggedIn();

        if (! $loggedIn) {
            #return redirect(url('/'));
        }

        $translationReader = $translationFactory->getTranslationReader();
        $translationBag = $this->getBagByHash($translationReader, $hash);

        $baseLanguage = $this->config->get('app.locale');

        return view('translationFactory::file', compact('translationBag', 'currentItemKey', 'baseLanguage'));
    }
    
    /**
     * Updatse a translation item
     *
     * @param Request $request
     * @param string  $hash
     * @param string  $currentItemKey
     */
    public function update(Request $request, string $hash, string $currentItemKey)
    {
        // TODO Implement this
        $translation = $request->input('translation');
        die();
    }

    /**
     * Returns a translation bag that is identified by its hash
     *
     * @param TranslationReaderInterface $translationReader
     * @param string                     $hash
     * @return \ChrisKonnertz\TranslationFactory\TranslationBag
     * @throws \Exception
     */
    public function getBagByHash(TranslationReaderInterface $translationReader, string $hash)
    {
        $translationBags = $translationReader->readAll();

        $currentBag = null;
        foreach ($translationBags as $translationBag) {
            if ($translationBag->getHash() === $hash) {
                $currentBag = $translationBag;
                break;
            }
        }

        if ($currentBag === null) {
            throw new \Exception('Could not find a translation bag with this hash: '.$hash);
        }

        return $currentBag;
    }

}
