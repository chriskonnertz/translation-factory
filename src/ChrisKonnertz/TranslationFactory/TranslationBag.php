<?php

namespace ChrisKonnertz\TranslationFactory;

class TranslationBag
{

    /**
     * Contains the translations
     *
     * @var array
     */
    protected $translations;

    /**
     * Stores the name of the file that is the source of this translation bag
     *
     * @var string
     */
    protected $sourceFile;

    /**
     * TranslationBag constructor.
     *
     * @param string[]  $translations
     * @param string $sourceFile
     */
    public function __construct(array $translations, string $sourceFile)
    {
        $this->setTranslations($translations);
        $this->setSourceFile($sourceFile);
    }

    /**
     * @return array
     */
    public function getTranslations() : array
    {
        return $this->translations;
    }

    /**
     * @param string[] $translations
     * @throws \Exception
     */
    public function setTranslations(array $translations)
    {
        foreach ($translations as $key => $translation) {
            if (! is_string($translation)) {
                throw new \InvalidArgumentException(
                    'Value of translation item with key "'.$key.'" in file "'.$this->sourceFile.'" is not a string'
                );
            }
        }

        $this->translations = $translations;
    }

    /**
     * @return string
     */
    public function getSourceFile() : string
    {
        return $this->sourceFile;
    }

    /**
     * @param string $sourceFile
     */
    public function setSourceFile(string $sourceFile)
    {
        if (trim($sourceFile) === '') {
            throw new \InvalidArgumentException('The name of the source file cannot be an empty string');
        }

        $this->sourceFile = $sourceFile;
    }

}