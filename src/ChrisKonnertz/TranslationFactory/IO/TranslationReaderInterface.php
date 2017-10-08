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

}