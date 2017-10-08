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
     * Stores the name of the base directory of the source file
     *
     * @var string
     */
    protected $sourceDir;

    /**
     * Stores the name of the file that is the source of this translation bag
     *
     * @var string
     */
    protected $sourceFile;

    /**
     * TranslationBag constructor.
     *
     * @param string[] $translations
     * @param string   $sourceDir
     * @param string   $sourceFile
     */
    public function __construct(array $translations, string $sourceDir, string $sourceFile)
    {
        $this->setSourceDir($sourceDir);
        $this->setSourceFile($sourceFile);
        $this->setTranslations($translations);
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
        $this->validateTranslationItem('', $translations);

        $this->translations = $translations;
    }


    protected function validateTranslationItem($key, $value, $namespace = '')
    {
        if (! is_string($value)) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $this->validateTranslationItem($subKey, $subValue, $key ? $key.'.' : '');
                }
            } else {
                throw new \InvalidArgumentException(
                    'Value of translation item with key "'.$namespace.$key.'" in file "'.$this->sourceFile.
                    '" is not a string and not an array'
                );
            }
        }
    }

    /**
     * @return string
     */
    public function getSourceDir() : string
    {
        return $this->sourceDir;
    }

    /**
     * @param string $sourceDir
     */
    public function setSourceDir(string $sourceDir)
    {
        if (trim($sourceDir) === '') {
            throw new \InvalidArgumentException('The name of the source directory cannot be an empty string');
        }

        $this->sourceDir = $sourceDir;
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

    /**
     * Returns the (unique) name of the translation bag
     *
     * @return string
     */
    public function getName() : string
    {
        $pos = strlen($this->sourceDir);
        return substr($this->sourceFile, $pos);
    }

}