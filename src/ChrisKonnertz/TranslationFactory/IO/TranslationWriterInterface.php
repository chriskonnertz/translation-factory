<?php

namespace ChrisKonnertz\TranslationFactory\IO;

use ChrisKonnertz\TranslationFactory\TranslationBag;

interface TranslationWriterInterface
{
    /**
     * Writes the translations to translation files
     *
     * @param TranslationBag $translationBag  The translation bag with the translation
     * @param string         $customOutputDir If not an empty string, use this path to store the files
     * @throws \Exception
     */
    public function write(TranslationBag $translationBag, $customOutputDir = '');
}