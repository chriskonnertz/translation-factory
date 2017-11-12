<?php

namespace ChrisKonnertz\TranslationFactory\IO;

use ChrisKonnertz\TranslationFactory\TranslationBag;

interface TranslationWriterInterface
{
    public function write(TranslationBag $translationBag);
}