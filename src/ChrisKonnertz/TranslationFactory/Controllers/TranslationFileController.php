<?php

namespace ChrisKonnertz\TranslationFactory\Controllers;

use ChrisKonnertz\TranslationFactory\IO\TranslationReaderInterface;
use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
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

        if ($this->config->get(TranslationFactory::CONFIG_NAME.'.user_authentication') === true) {
            $loggedIn = $translationFactory->getUserManager()->isLoggedIn();

            if (!$loggedIn) {
                return redirect(url('/'));
            }
        }

        $translationReader = $translationFactory->getTranslationReader();
        $translationBag = $this->getBagByHash($translationReader, $hash);

        $baseLanguage = $this->config->get('app.locale');
        $targetLanguage = $translationFactory->getTargetLanguage();

        $data = compact('translationBag', 'currentItemKey', 'baseLanguage', 'targetLanguage');
        return view('translationFactory::file', $data);
    }

    /**
     * Updates a translation item
     *
     * @param Request $request
     * @param string  $hash
     * @param string  $currentItemKey
     */
    public function update(Request $request, string $hash, string $currentItemKey)
    {
        // TODO Add user checks

        $translation = $request->input('translation');

        // The translation value can be sent but be null, which is not a valid value,
        // so change it to an empty string instead
        if ($translation === null) {
            $translation = '';
        }

        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        $translationReader = $translationFactory->getTranslationReader();
        $translationBag = $this->getBagByHash($translationReader, $hash);

        $translationBag->setTranslation($translationFactory->getTargetLanguage(), $currentItemKey, $translation);

        $translationWriter = $translationFactory->getTranslationWriter();
        $translationWriter->write($translationBag);
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
