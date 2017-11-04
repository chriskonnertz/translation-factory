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
     * Unique hash that can be used to identify this translation bag
     *
     * @var string
     */
    protected $hash;

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

        $this->refreshHash();
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

    /**
     * Validates a translation item (including its sub items).
     * Throws an exception if the item is invalid.
     *
     * @param mixed $key
     * @param mixed $value
     * @param string $namespace
     */
    protected function validateTranslationItem($key, $value, string $namespace = '')
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
     * Getter for the source dir property
     *
     * @return string
     */
    public function getSourceDir() : string
    {
        return $this->sourceDir;
    }

    /**
     * Setter for the source dir property.
     * The source dir has to exist!
     *
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
     * Getter of the source file property
     *
     * @return string
     */
    public function getSourceFile() : string
    {
        return $this->sourceFile;
    }

    /**
     * Setter of the source file property.
     * The file name has to exist!
     *
     * @param string $sourceFile
     */
    public function setSourceFile(string $sourceFile)
    {
        if (trim($sourceFile) === '') {
            throw new \InvalidArgumentException('The name of the source file cannot be an empty string');
        }

        $this->sourceFile = $sourceFile;

        $this->refreshHash();
    }

    /**
     * Returns the name of the translation bag. For example: "validation.php"
     *
     * @return string
     */
    public function getName() : string
    {
        $pos = strlen($this->sourceDir);
        return substr($this->sourceFile, $pos);
    }

    /**
     * Returns the title of the translation bag which is nicer to read than the name.
     * For example: "Validation"
     *
     * @return string
     */
    public function getTitle() : string
    {
        $title = $this->getName();

        $pos = strpos($title, '.');
        if ($pos !== false and $pos > 0) {
            $title = substr($title, 0, $pos);
        }

        return title_case($title);
    }

    /**
     * Getter of the hash. The hash can be used to identify this translation abg amongst other bags.
     *
     * @return string
     */
    public function getHash() : string
    {
        return $this->hash;
    }

    /**
     * Refreshes the hash based on the source file name.
     *
     * @void
     */
    protected function refreshHash()
    {
        $this->hash = md5($this->sourceFile);
    }

}