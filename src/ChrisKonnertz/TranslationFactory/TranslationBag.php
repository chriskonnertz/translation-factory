<?php

namespace ChrisKonnertz\TranslationFactory;

/**
 * An object that is an instance of the class TranslationBag contains all translations that belong to
 * a specific translation file. It contains the translations in all available languages.
 * A language reader loads these translations and than create the translation bag.
 * To identify translation bags you may use their hash value that is accessible via the getHash() method.
 */
class TranslationBag
{

    /**
     * Contains the validated translations in all available languages
     *
     * @var array
     */
    protected $translations;

    /**
     * Stores the name of the base directory of the translation files (without language name)
     *
     * @var string
     */
    protected $sourceDir;

    /**
     * Stores the file name (with path) of the language file with the base language
     *
     * @var string
     */
    protected $baseFile;

    /**
     * Unique hash that can be used to identify this translation bag
     *
     * @var string
     */
    protected $hash;

    /**
     * TranslationBag constructor.
     *
     * @param string[] $translations Array with all translations in all available languages. Will be validated
     * @param string   $sourceDir    Path to the source directory (without language name)
     * @param string   $baseFile     File name (with path) of the language file with the base language
     */
    public function __construct(array $translations, string $sourceDir, string $baseFile)
    {
        $this->setSourceDir($sourceDir);
        $this->setBaseFile($baseFile);
        $this->setTranslations($translations);

        $this->refreshHash();
    }

    /**
     * Getter for the translations array.
     * You may use self::getTranslation() to access a specific key.
     *
     * @return array
     */
    public function getTranslations() : array
    {
        return $this->translations;
    }

    /**
     * Setter for the translations array
     * You may use self::setTranslation() to set a specific key.
     *
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
     * @param mixed $key        The key (without dots)
     * @param mixed $value      The value. Might be an array with sub values.
     * @param string $namespace A prefix for the key in dot notation
     * @return void
     * @throws \InvalidArgumentException
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
                    'Value of translation item with key "'.$namespace.$key.'" in file "'.$this->baseFile.
                    '" is not a string and not an array'
                );
            }
        }
    }

    /**
     * Returns a single item of the translation array.
     * Will return an array if the key targets an array.
     * Will return null if the key does not exist.
     *
     * @param string $language
     * @param string $key
     * @return string|array|null
     */
    public function getTranslation(string $language, string $key)
    {
        return array_get($this->translations, $language.'.'.$key);
    }

    /**
     * Sets or replaces a single item in the translations array
     *
     * @param string $language
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setTranslation(string $language, string $key, string $value)
    {
        $key = $language.'.'.$key;

        array_set($this->translations, $key, $value);
    }

    /**
     * Returns a shortened key without the language part. For example: Transforms 'en.accepted' to 'accepted'
     *
     * @param string $language
     * @param string $key
     * @return string
     */
    public function getShortKey(string $language, string $key)
    {
        return mb_substr($key, mb_strlen($language));
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
     * @return void
     */
    public function setSourceDir(string $sourceDir)
    {
        if (trim($sourceDir) === '') {
            throw new \InvalidArgumentException('The name of the source directory cannot be an empty string');
        }

        $this->sourceDir = $sourceDir;
    }

    /**
     * Getter of the base file property.
     * Returns the file name (with path) of the language file with the base language.
     * getBaseLanguage() returns the language code of this file.
     *
     * @return string
     */
    public function getBaseFile() : string
    {
        return $this->baseFile;
    }

    /**
     * Setter of the base file name (with path).
     * The file name has to exist.
     *
     * @param string $baseFile
     * @return void
     */
    public function setBaseFile(string $baseFile)
    {
        if (! file_exists($baseFile)) {
            throw new \InvalidArgumentException('The name of the base file cannot be an empty string');
        }

        $this->baseFile = $baseFile;

        $this->refreshHash();
    }

    /**
     * Returns the language code of the base language of this translation bag.
     * getBaseFile() returns the file of the base language.
     *
     * @return string
     */
    public function getBaseLanguage()
    {
        return basename(dirname($this->baseFile));
    }

    /**
     * Returns the name of the translation bag. For example: "validation.php"
     *
     * @return string
     */
    public function getName() : string
    {
        $pos = mb_strlen($this->sourceDir);
        return substr($this->baseFile, $pos);
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
     * Refreshes the hash based on the base file name.
     *
     * @return void
     */
    protected function refreshHash()
    {
        $this->hash = md5($this->baseFile);
    }

}