<?php

namespace ChrisKonnertz\TranslationFactory\IO;

// Note: We cannot use the contracts Illuminate\Contracts\Filesystem\Filesystem and
// Illuminate\Contracts\Translation\Translator here, they do not contain all the methods that we expect.
use ChrisKonnertz\TranslationFactory\TranslationBag;
use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;

class TranslationReader implements TranslationReaderInterface
{

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var string
     */
    protected $baseLanguage;

    /**
     * TranslationReader constructor.
     *
     * @param Translator $translator   An instance of Laravel's translator class
     * @param Filesystem $filesystem   An instance of Laravel's filesystem class
     * @param Config     $config       An instance of Laravel's config class
     * @param string     $baseLanguage The language code of the base language, for example 'en'
     */
    public function __construct(
        Translator $translator, Filesystem $filesystem, Config $config, string $baseLanguage = 'en'
    )
    {
        $this->translator = $translator;
        $this->filesystem = $filesystem;
        $this->config = $config;
        $this->baseLanguage = $baseLanguage;
    }

    /**
     * Reads all translations and returns them wrapped in a TranslationBag
     *
     * @return TranslationBag[]
     */
    public function readAll() : array
    {
        $mainDir = app()->langPath();
        $additionalDirs = $this->config->get(TranslationFactory::CONFIG_NAME.'.additional_paths');

        $dirs = array_merge([$mainDir], $additionalDirs);

        $translationBags = [];
        foreach ($dirs as $dir) {
            $newTranslationBags = $this->loadPath($dir, $this->baseLanguage);
            $translationBags = array_merge($translationBags, $newTranslationBags);
        }

        return $translationBags;
    }

    /**
     * Loads and returns translations from all files in a given directory and its subdirectories.
     *
     * @param string $basePath     The path to the language directory, for example /example/laravel/resource/lang
     * @param string $baseLanguage The language code of the base language, for example 'en'
     * @return TranslationBag[]
     * @throws \Exception
     */
    protected function loadPath(string $basePath, string $baseLanguage = 'en') : array
    {
        $basePath .= DIRECTORY_SEPARATOR.$baseLanguage;

        /** @var \Symfony\Component\Finder\SplFileInfo[] $files */
        $files = $this->filesystem->allFiles($basePath);

        $translationBags = [];

        foreach ($files as $file) {
            $content = $this->filesystem->getRequire($file->getPathname());

            if (! is_array($content)) {
                throw new \Exception('Translation file "'.$file->getPathname().'" does not contain an array');
            }

            $translationBags[] = new TranslationBag($content, $basePath.DIRECTORY_SEPARATOR, $file->getPathname());
        }

        return $translationBags;
    }

    /**
     * Getter for the base language property
     *
     * @return string
     */
    public function getBaseLanguage() : string
    {
        return $this->baseLanguage;
    }

    /**
     * Setter for the base language property
     *
     * @param string $baseLanguage
     */
    public function setBaseLanguage(string $baseLanguage)
    {
        $this->baseLanguage = $baseLanguage;
    }

}