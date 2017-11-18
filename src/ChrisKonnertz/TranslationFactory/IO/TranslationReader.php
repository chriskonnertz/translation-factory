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
     * The language code of the primary language
     *
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
        $mainRootDir = app()->langPath();
        $additionalRootDirs = $this->config->get(TranslationFactory::CONFIG_NAME.'.additional_paths');

        $rootDirs = array_merge([$mainRootDir], $additionalRootDirs);

        $translationBags = [];
        foreach ($rootDirs as $rootDir) {
            $newTranslationBags = $this->loadPath($rootDir, $this->baseLanguage);
            $translationBags = array_merge($translationBags, $newTranslationBags);
        }

        return $translationBags;
    }

    /**
     * Loads and returns translations from all files in a given directory and its subdirectories.
     *
     * @param string $rootDir      The path to the translations directory, for example /example/laravel/resource/lang
     * @param string $baseLanguage The language code of the base language, for example 'en'
     * @return TranslationBag[]
     * @throws \Exception
     */
    protected function loadPath(string $rootDir, $baseLanguage = 'en') : array
    {
        $translationBags = [];

        $langDirs = $this->filesystem->directories($rootDir);

        $baseLanguageDir = $rootDir.DIRECTORY_SEPARATOR.$baseLanguage;

        if (! $this->filesystem->exists($baseLanguageDir)) {
            throw new \Exception(
                'Error: No language directory for base language "'.$baseLanguage.'" in path "'.$rootDir.'"'
            );
        }

        /** @var \Symfony\Component\Finder\SplFileInfo[] $files */
        $baseFiles = $this->filesystem->allFiles($baseLanguageDir);
        foreach ($baseFiles as $baseFile) {
            if (strtolower($baseFile->getExtension()) !== 'php') {
                continue;
            }

            $translations = [];

            foreach ($langDirs as $langDir) {
                $language = basename($langDir);
                $filename = $langDir.DIRECTORY_SEPARATOR.$baseFile->getFilename();

                if ($this->filesystem->exists($filename)) {
                    $content = $this->filesystem->getRequire($filename);

                    if (! is_array($content)) {
                        throw new \Exception('Translation file "'.$filename.'" does not contain an array');
                    }

                    $translations[$language] = $content;
                }
            }

            $translationBags[] = new TranslationBag($translations, $rootDir.DIRECTORY_SEPARATOR, $baseFile);
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