<?php

namespace ChrisKonnertz\TranslationFactory\IO;

interface LanguageDetectorInterface
{
    /**
     * Finds all language directories and based on them return the names of all available languages
     *
     * @return string[]
     */
    public function detect() : array;
}