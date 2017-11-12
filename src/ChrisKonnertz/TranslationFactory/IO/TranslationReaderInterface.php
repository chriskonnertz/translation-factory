<?php

namespace ChrisKonnertz\TranslationFactory\IO;

use ChrisKonnertz\TranslationFactory\TranslationBag;

interface TranslationReaderInterface
{

    /**
     * Reads all translations and returns them wrapped in a TranslationBag
     *
     * @return TranslationBag[]
     */
    public function readAll() : array;

    /**
     * Getter for the base language property
     *
     * @return string
     */
    public function getBaseLanguage() : string;

    /**
     * Setter for the base language property
     *
     * @param string $baseLanguage
     */
    public function setBaseLanguage(string $baseLanguage);

}