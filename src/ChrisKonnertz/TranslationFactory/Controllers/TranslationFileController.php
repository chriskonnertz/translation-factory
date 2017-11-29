<?php

namespace ChrisKonnertz\TranslationFactory\Controllers;

use ChrisKonnertz\TranslationFactory\IO\TranslationReaderInterface;
use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Log\Writer as Log;

class TranslationFileController extends AuthController
{

    /**
     * Index page of the package
     *
     * @param string $hash The hash value that identifies the translation bag
     * @return \Illuminate\View\View
     */
    public function index(string $hash)
    {
        $this->ensureAuth();

        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        $translationReader = $translationFactory->getTranslationReader();
        $translationBag = $this->getBagByHash($translationReader, $hash);

        $currentItemKey = null;
        $autoTranslation = null;
        $targetLanguage = $translationFactory->getTargetLanguage();
        $data = compact('translationBag', 'currentItemKey', 'targetLanguage', 'autoTranslation');
        return view('translationFactory::file', $data);
    }

    /**
     * Shows the translation file page with a text area for editing a translation item
     *
     * @param string $hash           The hash value that identifies the translation bag
     * @param string $currentItemKey The key of the current translation item (in dot notation)
     * @return \Illuminate\View\View
     */
    public function edit(string $hash, string $currentItemKey)
    {
        $this->ensureAuth();

        /** @var TranslationFactory $translationFactory */
        $translationFactory = app()->get('translation-factory');

        $translationReader = $translationFactory->getTranslationReader();
        $translationBag = $this->getBagByHash($translationReader, $hash);

        $baseLanguage = $this->config->get('app.locale');
        $targetLanguage = $translationFactory->getTargetLanguage();

        $autoTranslation = null;
        if (! $translationBag->hasTranslation($targetLanguage, $currentItemKey)) {
            if ($translationFactory->canTranslate(strtoupper($baseLanguage), strtoupper($targetLanguage))) {
                try {
                    $autoTranslation = $translationFactory->translate(
                        $translationBag->getTranslation($baseLanguage, $currentItemKey)
                    );
                } catch (\Exception $exception) {
                    // do nothing
                }
            }
        }

        $data = compact('translationBag', 'currentItemKey', 'baseLanguage', 'targetLanguage', 'autoTranslation');
        return view('translationFactory::file', $data);
    }

    /**
     * Updates a translation item
     *
     * @param Request $request        An instance of Laravel's request class
     * @param Log     $log            An instance of Laravel's logging class
     * @param string  $hash           The hash value that identifies the translation bag
     * @param string  $currentItemKey The key of the current translation item (in dot notation)
     */
    public function update(Request $request, Log $log, string $hash, string $currentItemKey)
    {
        $this->ensureAuth();

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

        if ($this->config->get(TranslationFactory::CONFIG_NAME.'.user_authentication') === true) {
            $log->info('User with ID '.$translationFactory->getUserManager()->getCurrentUserId().
                ' translated item "'.$currentItemKey.'" of file "'.$translationBag->getBaseFile().'" from "'.
                $translationBag->getBaseLanguage().'" into "'.$translationFactory->getTargetLanguage().'"');
        }
    }

    /**
     * Returns a translation bag that is identified by its hash
     *
     * @param TranslationReaderInterface $translationReader An instance of the translation reader interface
     * @param string                     $hash              The hash value that identifies the translation bag
     * @return \ChrisKonnertz\TranslationFactory\TranslationBag
     * @throws \Exception
     */
    public function getBagByHash(TranslationReaderInterface $translationReader, string $hash)
    {
        # TODO remove this ensureAuth() call?
        #$this->ensureAuth();

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
